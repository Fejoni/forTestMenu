<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use App\Models\FoodMenuDishProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuGenerateController extends Controller
{
    public function generateMenu(Request $request)
    {
        $data = json_decode($request->text, true);


        foreach ($data as $item){
            $dish = new Dish;
            $dish->name = $item['name'];
            $dish->calories = $item['calories'];
            $dish->protein = $item['proteins'];
            $dish->carbohydrates = $item['carbs'];
            $dish->fats = $item['fats'];
            $dish->portions = 1;
            $dish->weight = 100;
            $dish->save();

            foreach ($item['ingredients'] as $ingredient){
                $product = Product::query()->where('name', $ingredient['name'])->first();
                $unit = ProductUnit::query()->where('name', $ingredient['unit'])->first();
                if(!$unit){
                    $unit = new ProductUnit;
                    $unit->name =  $ingredient['unit'];
                    $unit->save();
                }
                if(!$product){
                    $product = new Product;
                    $product->name =  $ingredient['name'];
                    $product->unit_id =  $unit->uuid;
                    $product->save();
                }

                $dishProduct = new FoodMenuDishProduct;
                $dishProduct->quantity = $unit['count'] != null ? $unit['count'] : 1;
                $dishProduct->dish_id =  $dish->uuid;
                $dishProduct->product_id =  $product->uuid;
                $dishProduct->save();
            }

            $url = 'https://neuroimg.art/api/v1/generate';

            $headers = ['Content-Type: application/json'];

            $post_data = [
                "token"=>"36327fcc-de17-4307-a3b1-0aef239f50c4",
                //  "model"=>"AcornIsSpinningFLUX-DevV1.1",
                "model"=>"MaxRealFLux-v3.0fp8",
                "prompt"=>"Блюдо ".$item['name']." простое",
                "width"=> 512,
                "height"=> 512,
                "steps"=> 30,
                "stream"=> false
            ];

            $data_json = json_encode($post_data); // переводим поля в формат JSON

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);

            $result = curl_exec($curl);
            if($result['status'] == 'SUCCESS'){
                $dish->photo =  $result['image_url'];
                $dish->save();
            }

            dd($dish);
        }



    }
}
