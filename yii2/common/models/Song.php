<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\helpers\AdminHelper;
use yii\helpers\VarDumper;

/**
 * Song model
 *
 * @property integer $id
 * @property integer $singer_id
 * @property Singer $singer
 * @property string $title
 * @property string $slug
 * @property string $lyrics
 * @property integer $hit
 * @property integer $status
 * @property integer $created_at
 */
class Song extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;
    const STATUS_SINGER_PASSIVE = 2;

    public static $statuses = [
        self::STATUS_ACTIVE         => 'Active',
        self::STATUS_PASSIVE        => 'Passive',
        self::STATUS_SINGER_PASSIVE => 'Singer Passive',
    ];

    // Cache Durations
    const CD_LIST = 6*60;
    const CD_SINGER = 10*60;
    const CD_ITEM = 60*60;
    const CD_RANDOM_LIST = 60*60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%songs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['singer_id', 'title', 'lyrics'], 'required'],
            [['singer_id', 'slug'], 'unique', 'targetAttribute' => ['singer_id', 'slug']],
            ['singer_id', 'exist', 'targetClass' => Singer::class, 'targetAttribute' => ['singer_id' => 'id']],
            ['title', 'string', 'min' => 1, 'max' => 255],
            ['slug', 'string', 'min' => 1, 'max' => 150],
            ['slug', 'default', 'value' => 'n-a'],
            ['hit', 'number'],
            ['hit', 'default', 'value' => 0],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PASSIVE]],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false
            ],
        ];
    }

    /**
     * Finds songs with cache
     *
     * @param int $singer_id
     * @param bool $initial
     * @param string $order
     * @param int $pages
     *
     * @return int|Song[]
     */
    public static function getListWithCache($singer_id = 0, $initial = false, $order = 'name', $pages = 14)
    {
        $songs  = Song::find()->with('singer')->andWhere(['status' => self::STATUS_ACTIVE]);

        if ($singer_id > 0) {
            $songs->andWhere(['singer_id' => $singer_id]);
        }

        if (!empty($initial)) {
            if ($initial == '09') {
                $songs->andWhere("slug LIKE '0%' OR slug LIKE '1%' OR slug LIKE '2%' OR slug LIKE '3%' OR slug LIKE '4%' OR slug LIKE '5%' OR slug LIKE '6%' OR slug LIKE '7%' OR slug LIKE '8%' OR slug LIKE '9%'");
            } else {
                $songs->andWhere("slug LIKE '$initial%'");
            }
        }

        if ($pages === 'get_count') {
            return $songs->cache(self::CD_LIST)->count();
        } elseif (is_integer($pages) && $pages > 0) {
            $songs->offset(0)->limit($pages);
        } elseif (isset($pages->offset) && isset($pages->limit)) {
            $songs->offset($pages->offset)->limit($pages->limit);
        }

        if ($order == 'new') {
            $songs->orderBy("created_at DESC, id DESC");
        } elseif ($order == 'old') {
            $songs->orderBy("created_at ASC, id ASC");
        } elseif ($order == 'hit') {
            $songs->orderBy("hit DESC");
        } elseif ($order == 'name') {
            $songs->orderBy("slug ASC");
        }

        return $songs->cache(self::CD_LIST)->all();
    }

    /**
     * Finds a song by slug with cache
     *
     * @param $singer_slug
     * @param $song_slug
     *
     * @return Song
     */
    public static function findOneBySlugsWithCache($singer_slug, $song_slug)
    {
        return Yii::$app->cache->getOrSet('find_song_by_slugs_'.$singer_slug.'_'.$song_slug, function () use ($singer_slug, $song_slug) {

            $singer = Singer::findOneBySlugWithCache($singer_slug);

            if ($singer) {
                return Song::findOne(['singer_id' => $singer->id, 'slug' => $song_slug, 'status' => self::STATUS_ACTIVE]);
            }

            return false;

        }, self::CD_ITEM);
    }

    /**
     * Deletes a song by slugs on cache
     *
     * @param $singer_slug
     * @param $song_slug
     *
     * @return bool
     */
    public static function deleteCacheBySlugs($singer_slug, $song_slug)
    {
        return Yii::$app->cache->delete('find_song_by_slugs_'.$singer_slug.'_'.$song_slug);
    }

    /**
     * Finds a song random
     *
     * @return Song
     */
    public static function getRandomSong()
    {
        $songs = Yii::$app->cache->getOrSet('random_list', function () {

            $songs  = Song::find()->with('singer')->andWhere(['status' => self::STATUS_ACTIVE])
                                    ->orderBy('RAND()')->limit(500)->all();

            return array_values($songs);

        }, self::CD_RANDOM_LIST);

        return $songs[rand(0, count($songs)-1)];
    }

    /**
     * Pluses one to hit of a song
     *
     * @param $id
     * @return int
     */
    public static function plusHit($id)
    {
        return Song::updateAllCounters(['hit' => 1], ['id' => $id]);
    }

    /**
     * Creates a slug after controls there is or not
     *
     * @param $text
     * @param int $singer_id
     * @param int $c
     *
     * @return string
     */
    public function createSlug($text, $singer_id, $c = 0)
    {
        $slug   = AdminHelper::slugify($text);

        if ($c > 0) {
            $slug   = $slug.'-'.$c;
        }

        if ($slug != '') {
            $slug_query = self::find()->andWhere(['slug' => $slug])->andWhere(['singer_id' => $singer_id]);

            if ($slug_query->count() > 0) {
                return $this->createSlug($text, $singer_id, $c+1);
            }
        }

        return $slug;
    }


    /**
     * Relation
     */
    public function getSinger()
    {
        return $this->hasOne(Singer::class, ['id' => 'singer_id'])->cache(self::CD_SINGER);
    }


    /** Events */

    /**
     * beforeValidate
     *
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->slug == '' && $this->title != '') {
            $this->slug = $this->createSlug($this->title, $this->singer_id);
        }

        return parent::beforeValidate();
    }

    /**
     * beforeSave
     *
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->lyrics   = nl2br($this->lyrics);

        return parent::beforeSave($insert);
    }

    /**
     * afterDelete
     *
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\elasticsearch\Exception
     */
    public function afterDelete()
    {
        ElasticItem::deleteItem($this->id, 'song');

        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * afterSave
     *
     * @param bool $insert
     *
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        ElasticItem::saveItem($this->id, 'song', $this);

        // We delete the song's cache when saving
        if (!$insert) {
            self::deleteCacheBySlugs($this->singer->slug, $this->slug);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}