<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CategoryController extends Controller
{
    /**
     * @throws GuzzleException
     */
    public function result(Request $request): JsonResponse
    {
        $client = new Client();
        $category = Arr::get($request->all(), 'category');
        $word = strtolower(Arr::get($request->all(), 'word'));

        $apiKey = env('API_KEY');

        if($category == 'animals' || $category == 'country' || $category == 'celebrity')
        {
            $response = $client->get(sprintf('https://api.api-ninjas.com/v1/%s?name=%s', $category, $word), [
                'headers' => [
                    'x-api-key' => $apiKey,
                ],
            ]);
            $response = json_decode($response->getBody()->getContents(), true);
            return !empty($response) ? response()->json(true) : response()->json(false);
        }

        if($category == 'verbs' || $category == 'fruits' ||
            $category == 'professions' || $category == 'bodyParts' ||
            $category == 'objects'
        )
        {
            $path = storage_path(sprintf('/app/json/%s.json', $category));
            $json = json_decode(file_get_contents($path), true);
            $response = Arr::where($json[$category], function ($value) use ($word) {
                return strtolower($value['name']) == $word;
            });

            return !empty($response) ? response()->json(true) : response()->json(false);
        }
        return response()->json(false);
    }

    public function generateLetter(): JsonResponse
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetter = $alphabet[rand(0, strlen($alphabet) - 1)];
        return response()->json(['letter' => $randomLetter]);
    }
}
