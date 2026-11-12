<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach (DB::table('tclient')->pluck('client_id') as $clientId) {
            DB::table('tparameter')->insertOrIgnore([
                'client_id' => $clientId,
                'name' => 'verify_email_member',
                'description' => 'Verifikasi email anggota',
                'value' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('tparameter')->where('name', 'verify_email_member')->delete();
    }
};
