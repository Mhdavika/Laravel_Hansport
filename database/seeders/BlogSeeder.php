<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data lama
        Blog::truncate();

        $blogs = [
            [
                'title' => 'Trend Olahraga 2025',
                'image' => 'blog-1.jpg',
                'content' => 'Tahun 2025 menghadirkan babak baru dalam dunia olahraga, di mana teknologi dan gaya hidup aktif saling bersinergi. Atlet profesional kini memanfaatkan kecerdasan buatan untuk menganalisis performa mereka secara real-time. Mulai dari pelacakan gerakan otot menggunakan wearable devices, hingga pelatih virtual berbasis AI yang memberikan saran personal. Tidak hanya itu, e-sport dan olahraga berbasis augmented reality juga semakin diminati generasi muda. Dunia kini menyaksikan pergeseran dari aktivitas fisik konvensional ke format hybrid yang lebih interaktif dan terukur secara digital.',
                'author' => 'admin',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Pentingnya Sepatu yang Tepat',
                'image' => 'blog-2.jpg',
                'content' => 'Sepatu olahraga bukan hanya elemen gaya, melainkan kunci performa dan keselamatan. Banyak kasus cedera terjadi akibat pemilihan sepatu yang tidak sesuai dengan jenis aktivitas. Misalnya, sepatu lari didesain dengan bantalan empuk untuk menyerap hentakan, sementara sepatu basket lebih fokus pada ankle support untuk mencegah keseleo saat melompat. Selain itu, bentuk kaki dan postur tubuh juga berperan dalam menentukan sepatu ideal. Memilih sepatu yang salah bisa memperparah masalah lutut atau tulang belakang dalam jangka panjang. Oleh karena itu, konsultasi dengan ahli atau fitting langsung sangat disarankan sebelum membeli sepatu baru.',
                'author' => 'admin',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Tips Latihan Ringan di Rumah',
                'image' => 'blog-3.jpg',
                'content' => 'Tidak punya waktu ke gym bukan alasan untuk melewatkan olahraga. Latihan ringan di rumah bisa menjadi solusi praktis bagi siapa saja yang ingin tetap aktif. Mulailah dengan pemanasan seperti jalan di tempat, lompat tali ringan, atau dynamic stretching. Gunakan barang di sekitar rumah seperti botol air untuk dumbbell, atau kursi sebagai alat bantu squat. Latihan seperti push-up, plank, mountain climbers, dan wall sit bisa dilakukan tanpa alat sekalipun. Yang terpenting adalah konsistensi dan progresi bertahap. Jadwalkan 20-30 menit per hari, dan gunakan aplikasi pelacak kebugaran untuk memantau perkembangan.',
                'author' => 'admin',
                'published_at' => now(),
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
