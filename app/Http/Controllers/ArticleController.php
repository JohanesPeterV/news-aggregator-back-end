<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class ArticleController extends Controller
{

    public function personalizedArticles(Request $request)
    {
        $user = User::find($request->user()->id);
        $articles = Article::whereIn('source', $user->preferred_sources)
            ->orWhereIn('category', $user->preferred_categories)
            ->orWhereIn('author', $user->preferred_authors)
            ->get();

        return response()->json(['articles' => $articles]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::query();

        if ($searchTerm = $request->q) {
            $query->where(function ($innerQuery) use ($searchTerm) {
                $innerQuery->where('title', 'LIKE', "%$searchTerm%")
                    ->orWhere('content', 'LIKE', "%$searchTerm%")
                    ->orWhere('author', 'LIKE', "%$searchTerm%")
                    ->orWhere('source', 'LIKE', "%$searchTerm%");
            });
        }

        if ($category = $request->category) {
            $query->where('category', $category);
        }

        if ($startDate = $request->start_date && $endDate = $request->end_date) {
            $query->whereBetween('publish_date', [$startDate, $endDate]);
        }

        $perPage = $request->per_page ?? 10;
        $page = $request->page ?? 1;

        $articles = $query->paginate($perPage, ['*'], 'page', $page);

        $collection = $articles->items();

        foreach ($collection as $article) {
            $article->content = Str::limit(strip_tags($article->content), 200) . (Str::length($article->content) > 200 ? '...' : '');
        }


        return response()->json(['articles' => $articles]);
    }

    public function indexBasedOnPreferences(Request $request)
    {
        $user = User::find($request->user()->id);
        $preferences = $user->preferences ?? [];

        $articles = Article::whereIn('source', $preferences['preferred_sources'] ?? [])
            ->orWhereIn('category', $preferences['preferred_categories'] ?? [])
            ->orWhereIn('author', $preferences['preferred_authors'] ?? [])
            ->get();
        return response()->json(['articles' => $articles]);
    }


    public function distinctCategories()
    {
        $categories = Article::distinct('category')->pluck('category');
        return response()->json(['categories' => $categories]);
    }

    //     2. Article search and filtering: Users should be able to search for articles by keyword
    // and filter the results by date, category, and source.
    // 3. Personalized news feed: Users should be able to customize their news feed by
    // selecting their preferred sources, categories, and authors
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
