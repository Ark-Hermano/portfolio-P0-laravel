<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    //
    public function index(){


        $posts = Post::get();
        //dd($posts);


        return view('twitter.home', [
            'posts' => $posts,

        ]);
    }


}
