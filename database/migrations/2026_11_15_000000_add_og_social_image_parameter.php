<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing clients
        $clients = DB::table('tclient')->pluck('client_id');
        
        foreach ($clients as $clientId) {
            // Check if og_social_image parameter already exists
            $exists = DB::table('tparameter')
                ->where('client_id', $clientId)
                ->where('name', 'og_social_image')
                ->exists();
            
            if (!$exists) {
                DB::table('tparameter')->insert([
                    'client_id' => $clientId,
                    'name' => 'og_social_image',
                    'description' => 'Gambar Social Sharing (1200x630px, URL publik)',
                    'value' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tparameter')
            ->where('name', 'og_social_image')
            ->delete();
    }
};
