<?php
namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
            [['name', 'slug'], 'required'],
            ['name', 'string', 'min' => 2, 'max' => 150],
            ['slug', 'string', 'min' => 1, 'max' => 100],
            ['hit', 'number'],
            ['hit', 'default', 'value' => 0],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PASSIVE]],
        ];
    }

    /**
     * @return array
     */
    public static function selectList()
    {
        return ArrayHelper::map(Singer::find()->orderBy('name ASC')->all(), 'id', 'name');
    }

    /**
     * Relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSongs()
    {
        return $this->hasMany(Song::class, ['singer_id' => 'id']);
    }

}