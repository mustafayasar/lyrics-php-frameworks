<?php
namespace backend\models;


use common\models\Singer;
use yii\base\Model;

class SingerForm extends Model
{
    public $id;
    public $name;
    public $slug;
    public $hit;
    public $status;

    public static $statuses = [
        Singer::STATUS_ACTIVE => 'Active',
        Singer::STATUS_PASSIVE => 'Passive',
    ];


    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
        ];
    }
}