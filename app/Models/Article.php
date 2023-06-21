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
        'url',
        'source',
        'publish_date'
    ];

    public static function fromNewsCred($articleJson)
    {
        $contentJson=$articleJson['content'];
        $article=new Article();
        $article->title = $contentJson['title'];
        $article->content = $contentJson['description'];
        $article->author='';
        if(isset($articleJson['authors'])){
            foreach($articleJson['authors'] as $author){
                $article->author = $article->author+$author;
            }

        }
        $article->category = 'analytics';
        $article->url = $contentJson['link'];
        $article->source = $contentJson['source']['name'];
        $article->publish_date = Carbon::parse($contentJson['published_at'])->toDateTimeString();
        return $article;
    }

    public static function fromNewsApi($articleJson)
    {
        $article=new Article();
        $article->title = $articleJson['title'];
        $article->content = $articleJson['description'];
        $article->category = $articleJson['source']['name'] == 'techcrunch' ? 'technology' : 'gadget';
        $article->author = $articleJson['author'] ?? $articleJson['source']['name'];
        $article->url = $articleJson['url'];
        $article->source = $articleJson['source']['name'];
        $article->publish_date = Carbon::parse($articleJson['publishedAt'])->toDateTimeString();
        return $article;
    }

    public static function fromNytimesApi($articleJson)
    {
        $article=new Article();
        $article->title = $articleJson['title'];
        $article->content = $articleJson['abstract'];
        $article->category = $articleJson['section'];
        $article->author = Str::substr($articleJson['byline'], 3);
        $article->url = $articleJson['url'];
        $article->source = $articleJson['source'];
        $article->publish_date = Carbon::parse($articleJson['publishedAt'])->toDateTimeString();
        return $article;
    }


}
