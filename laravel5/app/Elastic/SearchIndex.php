<?php

namespace App\Elastic;

use ElasticSearcher\Abstracts\AbstractIndex;
use ElasticSearcher\ElasticSearcher;
use ElasticSearcher\Environment;

class SearchIndex extends AbstractIndex
{
    public static $searcher = false;
    public static $manager = false;

    public static $name = 'searches';

    /**
     * @return string
     */
    public function getName()
    {
        return self::$name;
    }

    public function setup()
    {
        $this->setTypes([
            'items' => [
                'properties' => [
                    'id'        => ['type' => 'integer'],
                    'own_id'    => ['type' => 'integer'],
                    'type'      => ['type' => 'string'],
                    'url'       => ['type' => 'string'],
                    'title'     => ['type' => 'string'],
                    'content'   => ['type' => 'string'],
                    'status'    => ['type' => 'integer'],
                ]
            ]
        ]);
    }

    public static function setSearcher()
    {
        $env      = new Environment([
            'hosts' => ['127.0.0.1']
        ]);

        self::$searcher = new ElasticSearcher($env);

        $searchIndex    = new SearchIndex();

        self::$searcher->indicesManager()->register($searchIndex);

        return self::$searcher;
    }

    public static function setManager()
    {
        self::$manager  = self::$searcher->documentsManager();

        return self::$manager;
    }

    public static function getSearcher()
    {
        if (!self::$searcher) {
            self::setSearcher();
        }

        return self::$searcher;
    }

    public static function getManager()
    {
        if (!self::$searcher) {
            self::setSearcher();
        }

        if (!self::$manager) {
            self::setManager();
        }

        return self::$manager;
    }

    public static function registerIndex()
    {
        $searchIndex    = new SearchIndex();
        $searcher       = self::getSearcher();

        $searcher->indicesManager()->indices();

        if ($searcher->indicesManager()->exists(SearchIndex::$name)) {

            $searcher->indicesManager()->register($searchIndex);

            $searcher->indicesManager()->delete(SearchIndex::$name);
        }

        if (!$searcher->indicesManager()->exists(SearchIndex::$name)) {

            $searcher->indicesManager()->register($searchIndex);

            $searcher->indicesManager()->create(SearchIndex::$name);
        }

        $searcher->indicesManager()->unregister(SearchIndex::$name);

        self::$searcher = false;
        self::$manager = false;
    }

}
