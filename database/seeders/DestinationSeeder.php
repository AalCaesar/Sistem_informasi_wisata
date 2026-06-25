<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pantai = Category::where('slug', 'pantai')->first();
        $gunung = Category::where('slug', 'gunung')->first();
        $kuliner = Category::where('slug', 'kuliner')->first();
        $sejarah = Category::where('slug', 'sejarah')->first();
        $tamanBermain = Category::where('slug', 'taman-bermain')->first();

        $destinations = [
            [
                'name' => 'Raja Ampat',
                'category_id' => $pantai ? $pantai->id : null,
                'location' => 'Papua Barat',
                'price' => 5000000,
                'description' => 'Kepulauan Raja Ampat adalah gugusan kepulauan yang berlokasi di barat bagian Semenanjung Dooberai, Pulau Papua. Raja Ampat terkenal di seluruh dunia karena keindahan bawah lautnya yang menakjubkan dan keanekaragaman hayati lautnya yang sangat kaya.',
                'image' => null,
            ],
            [
                'name' => 'Gunung Bromo',
                'category_id' => $gunung ? $gunung->id : null,
                'location' => 'Jawa Timur',
                'price' => 75000,
                'description' => 'Gunung Bromo adalah sebuah gunung berapi aktif di Jawa Timur, Indonesia. Gunung ini terkenal dengan pemandangan matahari terbit yang sangat indah serta kawahnya yang megah di tengah hamparan pasir laut yang luas.',
                'image' => null,
            ],
            [
                'name' => 'Candi Borobudur',
                'category_id' => $sejarah ? $sejarah->id : null,
                'location' => 'Magelang, Jawa Tengah',
                'price' => 50000,
                'description' => 'Candi Borobudur adalah candi Buddha terbesar di dunia, yang dibangun pada abad ke-9. Terletak di Magelang, Jawa Tengah, candi ini merupakan Situs Warisan Dunia UNESCO yang memikat jutaan wisatawan dari dalam dan luar negeri.',
                'image' => null,
            ],
            [
                'name' => 'Gudeg Yu Djum',
                'category_id' => $kuliner ? $kuliner->id : null,
                'location' => 'Yogyakarta',
                'price' => 35000,
                'description' => 'Gudeg Yu Djum adalah salah satu tempat kuliner gudeg legendaris paling populer di Yogyakarta. Menyajikan hidangan gudeg kering khas Jogja yang manis dan gurih, dimasak secara tradisional menggunakan tungku kayu bakar.',
                'image' => null,
            ],
            [
                'name' => 'Dunia Fantasi (Dufan)',
                'category_id' => $tamanBermain ? $tamanBermain->id : null,
                'location' => 'Ancol, Jakarta Utara',
                'price' => 250000,
                'description' => 'Dunia Fantasi atau disebut juga Dufan adalah tempat hiburan bertema keluarga dan taman bermain outdoor terbesar di Jakarta. Dufan memiliki puluhan wahana permainan berteknologi tinggi dan ramah anak.',
                'image' => null,
            ],
        ];

        foreach ($destinations as $dest) {
            Destination::create($dest);
        }
    }
}
