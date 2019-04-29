<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\helpers\AdminHelper;

/**
 * Singer model
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $hit
 * @property integer $status
 */
class Singer extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;

    public static $statuses = [
        self::STATUS_PASSIVE    => 'Passive',
        self::STATUS_ACTIVE     => 'Active',
    ];

    // Cache Durations
    const CD_LIST = 6*60;
    const CD_ITEM = 60*60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%singers}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['slug', 'unique'],
            ['name', 'string', 'min' => 2, 'max' => 150],
            ['slug', 'string', 'min' => 1, 'max' => 100],
            ['slug', 'default', 'value' => 'n-a'],
            ['hit', 'number'],
            ['hit', 'default', 'value' => 0],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PASSIVE]],
        ];
    }

    /**
     * For select list
     *
     * @return array
     */
    public static function selectList()
    {
        return ArrayHelper::map(Singer::find()->orderBy('name ASC')->all(), 'id', 'name');
    }

    /**
     * Finds singers with cache
     *
     * @param bool $initial
     * @param string $order
     * @param int $pages
     *
     * @return Singer[]|int
     */
    public static function getListWithCache($initial = false, $order = 'name', $pages = 14)
    {
        $singers    = Singer::find()->andWhere(['status' => self::STATUS_ACTIVE]);

        if (!empty($initial)) {
            if ($initial == '09') {
                $singers->andWhere("slug LIKE '0%' OR slug LIKE '1%' OR slug LIKE '2%' OR slug LIKE '3%' OR slug LIKE '4%' OR slug LIKE '5%' OR slug LIKE '6%' OR slug LIKE '7%' OR slug LIKE '8%' OR slug LIKE '9%'");
            } else {
                $singers->andWhere("slug LIKE '$initial%'");
            }
        }

        if ($pages === 'get_count') {
            return $singers->cache(self::CD_LIST)->count();
        } elseif (is_integer($pages) && $pages > 0) {
            $singers->offset(0)->limit($pages);
        } elseif (isset($pages->offset) && isset($pages->limit)) {
            $singers->offset($pages->offset)->limit($pages->limit);
        }

        if ($order == 'hit') {
            $singers->orderBy("hit DESC");
        } elseif ($order == 'name') {
            $singers->orderBy("slug ASC");
        }

        return $singers->cache(self::CD_LIST)->all();
    }

    /**
     * Finds a singer by slug with cache
     *
     * @param $slug
     *
     * @return Singer
     */
    public static function findOneBySlugWithCache($slug)
    {
        return Yii::$app->cache->getOrSet('find_singer_by_slug_'.$slug, function () use ($slug) {

            return Singer::findOne(['slug' => $slug, 'status' => self::STATUS_ACTIVE]);

        }, self::CD_ITEM);
    }

    /**
     * Deletes a singer by slug on cache
     *
     * @param $slug
     *
     * @return bool
     */
    public static function deleteCacheBySlug($slug)
    {
        return Yii::$app->cache->delete('find_singer_by_slug_'.$slug);
    }

    /**
     * Pluses one to hit of a singer
     *
     * @param $id
     * @return int
     */
    public static function plusHit($id)
    {
        return Singer::updateAllCounters(['hit' => 1], ['id' => $id]);
    }

    /**
     * Creates a slug after controls there is or not
     *
     * @param $text
     * @param int $c
     *
     * @return string
     */
    protected function createSlug($text, $c = 0)
    {
        $slug   = AdminHelper::slugify($text);

        if ($c > 0) {
            $slug   = $slug.'-'.$c;
        }

        if ($slug != '') {
            $slug_query = self::find()->andWhere(['slug' => $slug]);

            if ($slug_query->count() > 0) {
                return $this->createSlug($text, $c+1);
            }
        }

        return $slug;
    }

    /**
     * Relation with songs
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSongs()
    {
        return $this->hasMany(Song::class, ['singer_id' => 'id']);
    }


    /** Events */

    /**
     * beforeValidate
     *
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->slug == '' && $this->name != '') {
            $this->slug = $this->createSlug($this->name, $this->id);
        }

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
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
        ElasticItem::deleteItem($this->id, 'singer');

        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * afterSave
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        ElasticItem::saveItem($this->id, 'singer', $this);

        if (isset($changedAttributes['status'])) {
            // If a singer is active and it will be passive, we should make songs of the singer passive (SINGER_PASSIVE)
            if ($changedAttributes['status'] == 1 && $this->status == 0) {
                Song::updateAll(['status' => Song::STATUS_SINGER_PASSIVE],
                    ['singer_id' => $this->id, 'status' => Song::STATUS_ACTIVE]);
            }
            // We should do the direct opposite the previous one.
            elseif ($changedAttributes['status'] == 0 && $this->status == 1) {
                Song::updateAll(['status' => Song::STATUS_ACTIVE],
                    ['singer_id' => $this->id, 'status' => Song::STATUS_SINGER_PASSIVE]);
            }
        }

        // We delete the singer's cache when saving
        if (!$insert) {
            self::deleteCacheBySlug($this->slug);
        }

        parent::afterSave($insert, $changedAttributes);
    }

}