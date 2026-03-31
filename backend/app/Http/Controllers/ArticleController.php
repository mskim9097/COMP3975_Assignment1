<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ArticleController extends Controller
{
    #[OA\Get(
        path: '/api/articles',
        summary: 'Get all articles',
        tags: ['Articles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of articles'
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            Article::orderBy('created_at', 'desc')->get()
        );
    }

    #[OA\Post(
        path: '/api/articles',
        summary: 'Create a new article',
        tags: ['Articles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'content'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'My Article'),
                    new OA\Property(property: 'content', type: 'string', example: 'This is the content')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Article created')
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json($article, 201);
    }

    #[OA\Get(
        path: '/api/articles/{id}',
        summary: 'Get one article',
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Article ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Article found'),
            new OA\Response(response: 404, description: 'Article not found')
        ]
    )]
    public function show(string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }

    #[OA\Put(
        path: '/api/articles/{id}',
        summary: 'Update an article',
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Article ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'content'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated Article'),
                    new OA\Property(property: 'content', type: 'string', example: 'Updated content')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Article updated'),
            new OA\Response(response: 404, description: 'Article not found')
        ]
    )]
    public function update(Request $request, string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    #[OA\Delete(
        path: '/api/articles/{id}',
        summary: 'Delete an article',
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Article ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Article deleted'),
            new OA\Response(response: 404, description: 'Article not found')
        ]
    )]
    public function destroy(string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();

        return response()->json([
            'success' => true
        ]);
    }
}