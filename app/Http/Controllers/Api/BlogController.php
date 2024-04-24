<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BlogController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', only: ['store', 'update', 'destroy']),
        ];
    }

    public function index()
    {
        $blogs = Blog::with('user')->get();

        return response()->json([
            "data" => $blogs,
            "data_count" => $blogs->count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $blog = Blog::create([
            ...$validatedData,
            'user_id' => 1
        ]);

        return response()->json([
            "data" => $blog,
            "message" => "Blog created successfully!"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $blog->load('user');

        return response()->json([
            "data" => $blog,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $Blog)
    {

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
        ]);

        $Blog->update($validatedData);

        return response()->json([
            "data" => $Blog,
            "message" => "Blog updated successfully!"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response()->json(null, 204);
    }
}
