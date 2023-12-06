<?php

namespace App\Http\Controllers;

use App\Events\LetterMessage;
use App\Events\StopMessage;
use App\Models\Result;
use App\Models\Room;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * @throws GuzzleException
     */
    public function calculate(Request $request, $pin): JsonResponse
    {
        $client = new Client();
        $apiKey = env('API_KEY');
        $category = Arr::get($request->all(), 'category');
        $letter = strtolower(Arr::get($request->all(), 'letter'));
        $userResult = [];

        if($category == 'animals' || $category == 'country' || $category == 'celebrity') {
            $results = Arr::get($request->all(), 'data');
            foreach ($results as $result) {
                $word = strtolower(Arr::get($result, 'word'));
                $user = Arr::get($result, 'user');

                $response = $client->get(sprintf('https://api.api-ninjas.com/v1/%s?name=%s', $category, $word), [
                    'headers' => [
                        'x-api-key' => $apiKey,
                    ],
                ]);

                $response = json_decode($response->getBody()->getContents(), true);
                $userResult[$category][] = $this->checkAnswers($response, $results, $user, $word, $category, $letter);
            }
        }

        if($category == 'verbs' || $category == 'fruits' ||
            $category == 'professions' || $category == 'bodyParts' ||
            $category == 'objects'
        ) {
            $results = Arr::get($request->all(), 'data');
            foreach ($results as $result) {
                $word = strtolower(Arr::get($result, 'word'));
                $user = Arr::get($result, 'user');
                $path = storage_path(sprintf('/app/json/%s.json', $category));
                $json = json_decode(file_get_contents($path), true);

                $response = Arr::where($json[$category], function ($value) use ($word) {
                    return strtolower($value['name']) == $word;
                });

                $userResult[] = $this->checkAnswers($response, $results, $user, $word, $category, $letter);
            }
        }

        $userResult = Result::create(['category' => $userResult, 'pin' => $pin]);

        return response()->json($userResult);
    }

    public function getResultsByCategory($pin): array
    {
        return Result::where(['pin' => $pin])->get()->toArray();
    }

    public function calculateTotal($pin): JsonResponse
    {
        $results = $this->getResultsByCategory($pin);
        $userPoints = [];

        foreach ($results as $category => $categoryResults) {
            foreach ($categoryResults['category'] as $result) {
                $user = $result['user'];
                $points = $result['points'];

                if (!isset($userPoints[$user])) {
                    $userPoints[$user] = 0;
                }

                $userPoints[$user] += $points;
            }
        }

        return response()->json($userPoints);
    }

    public function generateLetter(): JsonResponse
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetter = $alphabet[rand(0, strlen($alphabet) - 1)];
        event(new LetterMessage($randomLetter));
        return response()->json(['letter' => $randomLetter]);
    }

    private function hasDuplicateWord($data): array
    {
        $wordsUsed = [];
        $usersWithRepeatedWords = [];

        foreach ($data as $item) {
            $word = $item['word'];
            $user = $item['user'];

            if (isset($wordsUsed[$word]) && $wordsUsed[$word] !== $user) {
                $usersWithRepeatedWords[] = $user;
                $usersWithRepeatedWords[] = $wordsUsed[$word];
            }
            $wordsUsed[$word] = $user;
        }

        return $usersWithRepeatedWords;
    }

    private function checkAnswers(array $response, array $results, $user, $word, $category, $letter): array
    {
        $firstLetter = substr($word, 0, 1);

        if (!empty($response) && $firstLetter == $letter) {
            $users = $this->hasDuplicateWord($results);

            if (!empty($users) && in_array($user, $users)) {
                $userResult = ['user' => $user, 'points' => 5, 'word' => $word, 'category' => $category];
            } else {
                $userResult = ['user' => $user, 'points' => 10, 'word' => $word, 'category' => $category];
            }
        } else {
            $userResult = ['user' => $user, 'points' => 0, 'word' => $word, 'category' => $category];
        }
        return $userResult;
    }
}
