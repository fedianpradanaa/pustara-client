<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $socialImages = DB::table('tparameter')
            ->where('name', 'og_social_image')
            ->get();

        foreach ($socialImages as $row) {
            $value = trim((string) $row->value);

            if ($value !== '') {
                DB::table('tparameter')
                    ->where('client_id', $row->client_id)
                    ->where('name', 'og_image')
                    ->update(['value' => $value]);
            }
        }

        DB::table('tparameter')->where('name', 'og_social_image')->delete();
    }

    public function down(): void
    {
        // Parameter og_social_image sengaja tidak dikembalikan.
    }
};
