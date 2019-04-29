<?php

namespace App\Http\Controllers\Admin;

use App\Singer;
use App\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $singers_count  = Singer::count();
        $songs_count    = Song::count();

        return view('admin.home', [
            'singers_count' => $singers_count,
            'songs_count'   => $songs_count,
        ]);
    }
}
