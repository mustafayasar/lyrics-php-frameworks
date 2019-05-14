<?php

namespace App\Elastic;

use ElasticSearcher\Abstracts\AbstractQuery;

class SearchQuery extends AbstractQuery
{
    /**
     * Prepare the query. Add filters, sorting, ....
     */
    protected function setup()
    {
        $this->searchIn(SearchIndex::$name, 'items');
    }


    public static function saveItem($own_id, $type, $item)
    {
        if ($type == 'singer') {
            $elastic_id = $own_id + 100000000;
        } elseif ($type == 'song') {
            $elastic_id = $own_id + 200000000;
        } else {
            return false;
        }

        $e_item   = [
            'id'        => $elastic_id,
            'own_id'    => $own_id,
            'type'      => $type,
            'status'    => $item->status,
        ];

        if ($type == 'singer') {
            $e_item['url']      = route('singer_songs', $item->slug);
            $e_item['title']    = $item->name;
            $e_item['content']  = '';
        } elseif ($type == 'song') {
            $e_item['url']      = route('song_view', [$item->singer->slug, $item->slug]);
            $e_item['title']    = $item->title.' - '.$item->singer->name;
            $e_item['content']  = $item->lyrics;
        }

        $manager    = SearchIndex::getManager();

        $manager->updateOrIndex(SearchIndex::$name, 'items', $elastic_id, $e_item);
    }

    public static function deleteItem($own_id, $type)
    {
        if ($type == 'singer') {
            $elastic_id = $own_id + 100000000;
        } elseif ($type == 'song') {
            $elastic_id = $own_id + 200000000;
        } else {
            return false;
        }

        $manager    = SearchIndex::getManager();

        $manager->delete(SearchIndex::$name, 'items', $elastic_id);
    }

    public static function search($q)
    {
        $query  = new SearchQuery(SearchIndex::getSearcher());
        $query->searchIn(SearchIndex::$name, 'items');
        $query->set('filter.term.status', 1);
        $query->set('query.match', ['title' => $q]);

        $result = $query->run();

        return $result->getResults();
    }
}