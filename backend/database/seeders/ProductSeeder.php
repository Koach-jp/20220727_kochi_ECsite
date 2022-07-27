<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<4; $i++) { 
        $product = [
            'name' => 'サンダル'.($i+1),
            'price' => '3000',
            'stock' => '5',
            'title' => '毎日履きたくなる、サンダル',
            'description' => '朝の支度が、格段にラクになる一足。シンプルで飽きのこないチームコートは、クリーンでモダンなデザインが魅力。合わせたいのは、デニムやバギースウェット。スタイリッシュなレザーアッパーとフレッシュなカラーがどんなコーディネートでも引き締めてくれる。もちろん、履き心地の良さもいうことなし。',
                'img_path' => 'storage/product-img/beigesandal.jpg',
            ];
            Product::create($product);
        }
        Product::factory(152)->create();
    }
}
