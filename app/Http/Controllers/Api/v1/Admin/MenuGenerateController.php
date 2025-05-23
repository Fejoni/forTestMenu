<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuGenerateController extends Controller
{
    public function generateMenu(Request $request)
    {
        $data = json_decode($request->text, true);


        $url = 'https://neuroimg.art/api/v1/generate';

        $headers = ['Content-Type: application/json'];

        $post_data = [
            "token"=>"36327fcc-de17-4307-a3b1-0aef239f50c4",
            "model"=>"AcornIsSpinningFLUX-DevV1.1",
            "prompt"=>"Блюдо Батончики мюсли",
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

        $result = curl_exec($curl); //
        dd($result);
    }
}
