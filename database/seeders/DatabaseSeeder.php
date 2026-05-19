<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \Illuminate\Support\Facades\DB::table('books')->insert([
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'year' => 2005,
                'category' => 'Novel',
                'description' => 'Kisah perjuangan sepuluh anak miskin di Belitung yang penuh inspirasi.',
                'cover' => 'https://upload.wikimedia.org/wikipedia/id/8/8e/Laskar_pelangi_sampul.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra',
                'year' => 1980,
                'category' => 'Sastra Sejarah',
                'description' => 'Perjalanan Minke, seorang pemuda pribumi yang cerdas di era kolonial Hindia Belanda.',
                'cover' => 'https://upload.wikimedia.org/wikipedia/id/thumb/0/07/Bumi_Manusia_cover.jpg/220px-Bumi_Manusia_cover.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Penerbit Buku Kompas',
                'year' => 2018,
                'category' => 'Pengembangan Diri',
                'description' => 'Filsafat Yunani-Romawi Kuno untuk mental tangguh masa kini.',
                'cover' => 'https://images.tokopedia.net/img/cache/500-square/VqbcmM/2021/6/8/b6423985-7033-4f9d-ae4c-bbba48375e11.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
