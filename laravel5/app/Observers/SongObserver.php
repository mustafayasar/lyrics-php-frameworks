<?php

namespace App\Observers;

use App\Elastic\SearchQuery;
use App\Singer;
use App\Song;

class SongObserver
{
    /**
     * Handle the song "created" event.
     *
     * @param  \App\Song  $song
     * @return void
     */
    public function created(Song $song)
    {
        SearchQuery::saveItem($song->id, 'song', $song);
    }

    /**
     * Handle the song "updated" event.
     *
     * @param  \App\Song  $song
     * @return void
     */
    public function updated(Song $song)
    {
        SearchQuery::saveItem($song->id, 'song', $song);

        Song::deleteCacheBySlugs($song->singer->slug, $song->slug);
    }

    /**
     * Handle the song "deleted" event.
     *
     * @param  \App\Song  $song
     * @return void
     */
    public function deleted(Song $song)
    {
        SearchQuery::deleteItem($song->id, 'song');
    }

    /**
     * Handle the song "restored" event.
     *
     * @param  \App\Song  $song
     * @return void
     */
    public function restored(Song $song)
    {
        SearchQuery::saveItem($song->id, 'song', $song);
    }

    /**
     * Handle the song "force deleted" event.
     *
     * @param  \App\Song  $song
     * @return void
     */
    public function forceDeleted(Song $song)
    {
        SearchQuery::deleteItem($song->id, 'song');
    }
}
