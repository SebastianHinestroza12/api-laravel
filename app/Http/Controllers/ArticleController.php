<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ArticleCollection
    {
        $allArticles = Article::all();
        // return ArticleResource::make($allArticles);
        // return ArticleResource::collection(Article::paginate(10));
        // return response()->json(ArticleResource::collection(Article::paginate(10)));

        //Resources
        // return ArticleResource::collection($allArticles);

        //Collection
        return ArticleCollection::make($allArticles);
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
    public function show(Article $article)
    {
        return ArticleResource::make($article);
        // return response()->json(new ArticleResource($article));
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
