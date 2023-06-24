<?php

namespace Database\Seeders;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedFromNewsApi();
        $this->seedFromNewsCred();
        $this->seedFromNewsApi();
    }

    private function seedFromNewsCred(){
        $fetchResult = Http::get(
            'https://api.newscred.com/v2/feed/f29434f4ad757c32b966019d956c0e4c',
            [
                'format' => 'json',
            ]
        );
        $articlesJson = $fetchResult->json();
        foreach ($articlesJson['entries'] as $articleJson) {
            $article=Article::fromNewsCred($articleJson);
            $article->save();
        }
    }
    private function seedFromNewsApi()
    {

        $fetchResult = Http::get(
            'https://newsapi.org/v2/everything',
            [
                'domains' => 'techcrunch.com, engadget.com',
                'apiKey' => env('NEWS_API_KEY')
            ]
        );

        $articlesJson = $fetchResult->json();
        foreach ($articlesJson['articles'] as $articleJson) {
            $article=Article::fromNewsApi($articleJson);
            $article->save();
        }
    }

    private function seedFromNytimesApi()
    {

        $fetchResult = Http::get(
            'https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json',
            [
                'api-key' => env('NYTIMES_API_KEY')
            ]
        );

        $articlesJson = $fetchResult->json();
        foreach ($articlesJson['results'] as $articleJson) {
            $article=Article::fromNytimesApi($articleJson);
            $article->save();
        }
    }
}
