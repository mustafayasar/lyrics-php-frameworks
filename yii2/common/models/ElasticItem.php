<?php
namespace common\models;

use yii\elasticsearch\ActiveRecord;

/**
 * https://github.com/yiisoft/yii2-elasticsearch
 *
 * Class ElasticItem
 *
 * @package common\models
 */
class ElasticItem extends ActiveRecord
{
    public static $properties  = [
        'id'        => ['type' => 'integer'],
        'type'      => ['type' => 'string'],
        'url'       => ['type' => 'string'],
        'title'     => ['type' => 'string'],
        'content'   => ['type' => 'string'],
        'status'    => ['type' => 'integer'],
    ];

    public function attributes()
    {
        return array_keys(self::$properties);
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => self::$properties
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Create this model's index
     */
    public static function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->createIndex(static::index(), [
            "settings"  => [],
            'mappings'  => static::mapping(),
            //'warmers' => [ /* ... */ ],
            //'aliases' => [ /* ... */ ],
            //'creation_date' => '...'
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index(), static::type());
    }

    /**
     * To save an ElasticItem
     * This customizes an item (Singer or Song) for elastic model
     *
     * @param $id $id of model
     * @param $type 'song' or 'singer'
     * @param $item $this model of Singer or Song
     *
     * @return bool
     */
    public static function saveItem($id, $type, $item)
    {
        if ($type == 'singer') {
            $primaryKey = 'singer-'.$id;
        } elseif ($type == 'song') {
            $primaryKey = 'song-'.$id;
        } else {
            return false;
        }

        $elastic_item   = self::findOne($primaryKey);

        if (!$elastic_item) {
            $elastic_item               = new ElasticItem();
            $elastic_item->primaryKey   = $primaryKey;
        }

        $elastic_item->id   = $id;
        $elastic_item->type = $type;

        if ($type == 'singer') {
            $elastic_item->url      = serialize(['site/singer-songs', 'singer_slug' => $item->slug]);
            $elastic_item->title    = $item->name;
            $elastic_item->content  = '';
        } elseif ($type == 'song') {
            $elastic_item->url      = serialize(['site/song-view', 'singer_slug' => $item->singer->slug, 'song_slug' => $item->slug]);
            $elastic_item->title    = $item->title.' - '.$item->singer->name;
            $elastic_item->content  = $item->lyrics;
        }

        $elastic_item->status   = $item->status;

        return $elastic_item->save();
    }

    /**
     * To delete an ElasticItem
     *
     * @param $id $id of model
     * @param $type 'song' or 'singer
     *
     * @return bool|false|int
     *
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\elasticsearch\Exception
     */
    public static function deleteItem($id, $type)
    {
        if ($type == 'singer') {
            $primaryKey = 'singer-'.$id;
        } elseif ($type == 'song') {
            $primaryKey = 'song-'.$id;
        } else {
            return false;
        }

        $item   = self::findOne($primaryKey);

        if ($item) {
            return $item->delete();
        }

        return false;
    }

    /**
     * To search with a term
     *
     * @param $q
     *
     * @return array
     */
    public static function search($q)
    {
        return ElasticItem::find()->query(["match" => ["title" => $q]])->limit(200)->all();
    }
}