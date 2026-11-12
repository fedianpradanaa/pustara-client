<?php

namespace App\Http\Controllers\Auth;

use App\Logs;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Parameter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $client_id = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->client_id = config('app.client_id', '');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'birthday' => ['nullable', 'date'],
            'nik' => ['nullable', 'string', 'max:25'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'flag_approve' => $data['flag_approve'],
            'client_id' => $this->client_id,
            'email_verified_at' => $data['email_verified_at'] ?? null,
        ]);
    }

    public function mregist(Request $request)
    {
        $registrationEnabled = Parameter::where('client_id', $this->client_id)
            ->where('name', 'reg_member')
            ->value('value');

        if ((int) $registrationEnabled !== 1) {
            Log::channel('activity')->notice('member.registration.rejected', [
                'client_id' => $this->client_id,
                'reason' => 'registration_disabled',
            ]);
            return response()->json('Pendaftaran ditutup, silahkan hubungi petugas perpustakaan.', 403);
        }

        try {
            $validated = $this->validator($request->all())->validate();
        } catch (ValidationException $e) {
            Log::channel('activity')->notice('member.registration.rejected', [
                'client_id' => $this->client_id,
                'reason' => 'invalid_data',
                'fields' => array_keys($e->errors()),
            ]);
            throw $e;
        }

        $approvalRequired = (int) (Parameter::where('client_id', $this->client_id)
            ->where('name', 'app_reg_member')
            ->value('value') ?? 0) === 1;
        $verificationRequired = (int) (Parameter::where('client_id', $this->client_id)
            ->where('name', 'verify_email_member')
            ->value('value') ?? 1) === 1;

        $user = null;
        try {
            DB::transaction(function () use ($request, $validated, $approvalRequired, $verificationRequired, &$user) {
                $user = $this->create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'role' => 'member',
                    'flag_approve' => $approvalRequired ? 'N' : 'Y',
                    'email_verified_at' => $verificationRequired ? null : Carbon::now('Asia/Jakarta'),
                ]);

                $attributesCreated = DB::table('tattr_member')->insert([
                    'id' => $user->id,
                    'client_id' => $this->client_id,
                    'nik' => $request->nik,
                    'phone' => $request->phone,
                    'birthday' => $request->birthday,
                    'gender' => $request->gender,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ]);

                if (!$attributesCreated) {
                    throw new \RuntimeException('Member attributes could not be saved.');
                }
            });
        } catch (\Throwable $e) {
            Log::channel('activity')->error('member.registration.failed', [
                'client_id' => $this->client_id,
                'exception' => get_class($e),
            ]);
            return response()->json('Pendaftaran gagal! Silahkan coba lagi.', 500);
        }

        Log::channel('activity')->info('member.registration.succeeded', [
            'user_id' => $user->id,
            'client_id' => $this->client_id,
            'email_verification_required' => $verificationRequired,
            'approval_required' => $approvalRequired,
        ]);

        $message = $approvalRequired
            ? 'Pendaftaran berhasil! Akun anda menunggu persetujuan petugas.'
            : 'Pendaftaran berhasil! Silahkan masuk.';
        if ($verificationRequired) {
            try {
                event(new Registered($user));
                $user->sendEmailVerificationNotification();
                $message = $approvalRequired
                    ? 'Pendaftaran berhasil! Silahkan konfirmasi email. Akun juga menunggu persetujuan petugas.'
                    : 'Pendaftaran berhasil! Silahkan konfirmasi email anda sebelum masuk.';
            } catch (\Throwable $e) {
                Log::channel('activity')->warning('member.registration.verification_email_failed', [
                    'user_id' => $user->id,
                    'client_id' => $this->client_id,
                    'exception' => get_class($e),
                ]);
                $message = $approvalRequired
                    ? 'Pendaftaran berhasil, tetapi email verifikasi belum dapat dikirim. Akun menunggu persetujuan petugas dan verifikasi email.'
                    : 'Pendaftaran berhasil, tetapi email verifikasi belum dapat dikirim. Silahkan masuk dan minta kirim ulang email verifikasi.';
            }
        }

        return response()->json($message, 201);
    }
}
