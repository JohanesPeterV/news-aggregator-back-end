<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'author',
        'image_url',
        'url',
        'source',
        'publish_date'
    ];
    /**
     * @var mixed|string
     */

    public static function fromNewsCred($articleJson):Article
    {
        $contentJson = $articleJson['content'];
        $article = new Article();
        $article->title = $contentJson['title'];
        $article->content = $contentJson['description'];
        $article->author = '';
        $article->image_url = isset($contentJson['images'][0]['url']) ? $contentJson['images'][0]['url'] : '';
        if (isset($articleJson['authors'])) {
            foreach ($articleJson['authors'] as $author) {
                $article->author = $article->author + $author;
            }
        }
        $article->category = 'analytics';
        $article->url = $contentJson['link'] ?? '';
        $article->source = $contentJson['source']['name'];
        $article->publish_date = Carbon::parse($contentJson['published_at'])->toDateTimeString();
        return $article;
    }

    public static function fromNewsApi($articleJson):Article
    {
        $article = new Article();
        $article->title = $articleJson['title'];
        $article->content = $articleJson['description'];
        $article->category = $articleJson['source']['name'] == 'techcrunch' ? 'technology' : 'gadget';
        $article->author = $articleJson['author'] ?? $articleJson['source']['name'];
        $article->url = $articleJson['url'] ?? '';
        $article->image_url = $articleJson['urlToImage'];
        $article->source = $articleJson['source']['name'];
        $article->publish_date = Carbon::parse($articleJson['publishedAt'])->toDateTimeString();
        return $article;
    }

    public static function fromNytimesApi($articleJson):Article
    {
        $article = new Article();
        $article->title = $articleJson['title'];
        $article->content = $articleJson['abstract'];
        $article->category = $articleJson['section'];
        $article->author = Str::substr($articleJson['byline'], 3);
        $article->url = $articleJson['url'] ?? '';
        $article->image_url = isset($articleJson['multimedia'][0]['url']) ? $articleJson['multimedia'][0]['url'] : '';

        $article->source = $articleJson['source'];
        $article->publish_date = Carbon::parse($articleJson['publishedAt'])->toDateTimeString();
        return $article;
    }
}
