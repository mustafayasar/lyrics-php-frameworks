<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Elastic\SearchIndex;
use App\Elastic\SearchQuery;
use App\Singer;
use App\Song;

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

        $query  = new SearchQuery(SearchIndex::getSearcher());
        $result = $query->run();
        $search_items_count = $result->getTotal();

        return view('admin.home', [
            'singers_count'         => $singers_count,
            'songs_count'           => $songs_count,
            'search_items_count'    => $search_items_count,
        ]);
    }



    /**
     * Copies Data to Elastic from Mysql
     *
     * @return \yii\web\Response
     */
    public function mysqlToElastic()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        SearchIndex::registerIndex();

        sleep(8);

        $singers    = Singer::all(['id', 'slug', 'name', 'status']);

        foreach ($singers as $singer) {
            SearchQuery::saveItem($singer->id, 'singer', $singer);
        }

        sleep(8);

        $songs      = Song::with('singer')->select(['id', 'slug', 'singer_id', 'title', 'lyrics', 'status'])->get();

        foreach ($songs as $song) {
            SearchQuery::saveItem($song->id, 'song', $song);
        }

        sleep(8);

        return redirect(route('admin.home'))->with('success', 'Mysql tables are copied to Elastic');
    }

    /**
     * Flushes Redis
     *
     * @return \yii\web\Response
     */
    public function flushRedis()
    {
        Cache::flush();

        return redirect(route('admin.home'))->with('success', 'Cache is flushed');
    }
}
