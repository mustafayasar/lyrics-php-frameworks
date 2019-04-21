<?php
namespace common\models;

use yii\db\ActiveRecord;

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
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PASSIVE]],
        ];
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