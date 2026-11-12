<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $client_id  = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
		$this->client_id = config('app.client_id', '');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        Log::channel('activity')->info('member.login.succeeded', [
            'user_id' => $user->id,
            'client_id' => $this->client_id,
            'role' => $user->role,
        ]);

        if ($user && $user->role != 'member') {
            return redirect('/admin');
        }

        return redirect($this->redirectTo);
    }

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            Log::channel('activity')->notice('member.login.failed', [
                'client_id' => $this->client_id,
                'reason' => 'invalid_request',
            ]);
            throw $e;
        }

        $user = User::where('email', $request->email)->where('client_id', $this->client_id)->first();

		if (!$user) {
            Log::channel('activity')->notice('member.login.failed', [
                'client_id' => $this->client_id,
                'reason' => 'invalid_credentials',
            ]);

			if($request->has('from') && $request->input('from') == 'member'){
				return response()->json([
					'message' => 'Gagal Login.',
					'errors' => [
						'email' => [
							'Email atau password salah.'
						]
					]
				], 422);
			}

			return redirect()->back()->withErrors(['email' => 'Email atau password salah.'])
            	->withInput(['email' => $request->email]);
		}

        if ($user && $user->flag_approve === 'N') {
            Log::channel('activity')->notice('member.login.blocked', [
                'user_id' => $user->id,
                'client_id' => $this->client_id,
                'reason' => 'approval_required',
            ]);
            return response()->json(['message' => 'Akun anda belum di approve, silahkan hubungi petugas perpustakaan.'], 403);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
			return $this->authenticated($request, Auth::user());
		}

        if ($user && $user->role === 'member') {
            Log::channel('activity')->notice('member.login.failed', [
                'user_id' => $user->id,
                'client_id' => $this->client_id,
                'reason' => 'invalid_credentials',
            ]);

            return response()->json([
                'message' => 'Gagal Login.',
                'errors' => [
                    'email' => [
                        'Email atau password salah.'
                    ]
                ]
            ], 422);
        }

        Log::channel('activity')->notice('member.login.failed', [
            'user_id' => $user->id,
            'client_id' => $this->client_id,
            'reason' => 'invalid_credentials',
        ]);

        return redirect()->back()->withErrors(['email' => 'Email atau password salah.'])
            ->withInput(['email' => $request->email]);
    }
}
