<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{

    public function index()
    {

        if (request('tag')){
            $articles = Tag::where('name', \request('tag'))->firstOrFail()->articles;
        }
        else{
            $articles = Article::latest()->get();
        }
        // Render a list of a resource


        return view('articles.index', ['articles' => $articles]);

    }

    public function show(Article $article)
    {
        // show a single resource



        return view('articles.show', ['article' => $article]);
    }

    public function create()
    {
        // Shows a view to create a new resource
        return view('articles.create', [
            'tags' => Tag::all()
        ]);
    }

    public function store()
    {

        $this->validateArticle();

        // Persists the new resource
        $article = new Article(['title', 'excerpt', 'body']);
        $article->user_id = 2;
        $article->save();

        $article->tags()->attach(request('tags'));
        //Article::create($this->validateArticle());
        return redirect(route('articles.index'));
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Article $article)
    {

        $article->update($this->validateArticle());

        return redirect($article->path());

    }

    public function destroy()
    {
        // Delete the Resource
    }


    public function validateArticle(): array
    {
        return request()->validate([
            'title'   => 'required',
            'excerpt' => 'required',
            'body'    => 'required',
            'tags'    => 'exists:tags,id'
        ]);
    }

//    public function list()
//    {
//        return Article::all();
//    }
}
