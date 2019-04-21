<?php
namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Singer model
 *
 * @property integer $id
 * @property integer $singer_id
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
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    // conditions appended by default (can be skipped)
    public function init()
    {
        parent::init();
    }

    // ... add customized query methods here ...

    public function active()
    {
        return $this->andOnCondition(['status' => 1]);
    }

    /**
     * Relation
     */
    public function getSinger()
    {
        return $this->hasOne(Singer::class, ['id' => 'singer_id']);
    }

}