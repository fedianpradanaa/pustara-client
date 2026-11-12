<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $parameters = [
        'site_tagline' => 'Tagline perpustakaan',
        'og_title' => 'Judul Open Graph',
        'og_description' => 'Deskripsi Open Graph',
        'og_image' => 'Gambar Open Graph (URL publik)',
        'social_facebook' => 'URL Facebook',
        'social_instagram' => 'URL Instagram',
        'social_youtube' => 'URL YouTube',
        'social_tiktok' => 'URL TikTok',
        'social_whatsapp' => 'URL WhatsApp',
    ];

    public function up(): void
    {
        $now = now();
        $clientIds = DB::table('tclient')->pluck('client_id');

        foreach ($clientIds as $clientId) {
            foreach ($this->parameters as $name => $description) {
                DB::table('tparameter')->insertOrIgnore([
                    'client_id' => $clientId,
                    'name' => $name,
                    'description' => $description,
                    'value' => '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('tparameter')
            ->whereIn('name', array_keys($this->parameters))
            ->delete();
    }
};
