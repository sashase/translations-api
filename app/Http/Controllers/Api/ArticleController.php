<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyArticleRequest;
use App\Http\Requests\IndexArticleRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexArticleRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(IndexArticleRequest $request): AnonymousResourceCollection
    {
        $language = $request->validated('language');
        $perPage = $request->validated('per_page') ?? Article::getModel()->getPerPage();

        $articles = Article::with(['translations' => function ($query) use ($language) {
            $query->where('language_code', $language);
        }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreArticleRequest $request
     * @return ArticleResource
     */
    public function store(StoreArticleRequest $request): ArticleResource
    {
        $translationsData = $request->validated('translations', []);

        return DB::transaction(function () use ($request, $translationsData) {
            $article = Article::create();

            foreach ($translationsData as $translationData) {
                $article->translations()->create($translationData);
            }

            return new ArticleResource($article);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return ArticleResource
     */
    public function show(string $id): ArticleResource
    {
        return new ArticleResource(Article::with('translations')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArticleRequest $request
     * @param string $id
     * @return ArticleResource
     */
    public function update(UpdateArticleRequest $request, string $id): ArticleResource
    {
        $article = Article::findOrFail($id);
        return DB::transaction(function () use ($request, $article, $id) {

            foreach ($request->validated('translations') as $translationData) {
                $languageCode = $translationData['language_code'];
                $translation = $article->translations()
                    ->where('language_code', $languageCode)
                    ->firstOrFail();

                $translation->update([
                    'title' => $translationData['title'],
                    'text' => $translationData['text'],
                ]);
            }

            $article->touch();

            return new ArticleResource($article);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyArticleRequest $request
     * @return JsonResponse
     */
    public function destroy(DestroyArticleRequest $request): JsonResponse
    {
        $articles = $request->validated('articles', []);
        return DB::transaction(function () use ($articles) {

            ArticleTranslation::whereIn('article_id', $articles)->delete();
            Article::whereIn('id', $articles)->delete();

            return response()->json(['message' => 'Articles deleted successfully'], Response::HTTP_OK);
        });
    }
}
