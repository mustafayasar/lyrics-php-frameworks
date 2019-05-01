<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Singer;
use App\Song;
use App\Elastic\SearchQuery;

class SiteController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $songs  = Song::getListWithCache(0, false, 'hit', 8);

        return view('site.home', ['songs' => $songs]);
    }

    /**
     * @param $i
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function singers($i)
    {
        $letters = config('app.letters');

        if ($i == 'hit') {
            $initial    = false;
            $order      = 'hit';
            $title      = 'Hit Singers';
        } elseif (isset($letters[$i])) {
            $initial    = $i;
            $order      = 'name';
            $title      = 'Singers Beginning with '.$letters[$i];
        } else {
            return abort(404);
        }

        $singers    = Singer::getListWithCache($initial, $order, 20);

        return view('site.singers', [
            'title'     => $title,
            'letters'   => $letters,
            'singers'   => $singers
        ]);
    }

    /**
     * @param $i
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function songs($i)
    {
        $letters = config('app.letters');

        if ($i == 'hit') {
            $initial    = false;
            $order      = 'hit';
            $title      = 'Hit Songs';
        } elseif (isset($letters[$i])) {
            $initial    = $i;
            $order      = 'name';
            $title      = 'Songs Beginning with '.$letters[$i];
        } else {
            return abort(404);
        }

        $songs  = Song::getListWithCache(0, $initial, $order, 20);

        return view('site.songs', [
            'title'     => $title,
            'letters'   => $letters,
            'songs'     => $songs
        ]);
    }

    /**
     * @param $singer_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function singerSongs($singer_slug)
    {
        $singer = Singer::findOneBySlugWithCache($singer_slug);

        if ($singer) {
            $songs  = Song::getListWithCache($singer->id, false, 'name', 6);

            if (Session::get('last_page') != URL::current()) {
                Singer::plusHit($singer->id);
            }

            Session::put('last_page', URL::current());

            return view('site.singer-songs', [
                'singer'    => $singer,
                'songs'     => $songs
            ]);
        }

        return abort(404);
    }

    /**
     * @param $singer_slug
     * @param $song_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function songView($singer_slug, $song_slug)
    {
        $song = Song::findOneBySlugsWithCache($singer_slug, $song_slug);

        if ($song) {
            $song->hit  = $song->hit + 1;

            if (Session::get('last_page') != URL::current()) {
                Song::plusHit($song->id);
                Singer::plusHit($song->singer->id);
            }

            Session::put('last_page', URL::current());

            return view('site.song-view', [
                'song'  => $song
            ]);
        }

        return abort(404);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function randomSongView()
    {
        $song = Song::getRandomSong();

        if ($song) {
            $song->hit  = $song->hit + 1;

            if (Session::get('last_page') != URL::current()) {
                Song::plusHit($song->id);
                Singer::plusHit($song->singer->id);
            }

            Session::put('last_page', URL::current());

            return view('site.song-view', [
                'song'  => $song
            ]);
        }

        return abort(404);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $q      = Input::get('q');
        $result = SearchQuery::search($q);

        return view('site.search', [
            'q'         => $q,
            'result'    => $result
        ]);
    }


}