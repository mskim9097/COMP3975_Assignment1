<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        $articles = \App\Models\Article::orderBy('created_at', 'desc')->get(['id', 'title', 'created_at']);
        return view('admin', ['articles' => $articles]);
    }
}
