<?php

use yii\db\Migration;

/**
 * Class m190421_133431_create_first_user
 */
class m190421_133431_create_first_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $first_user = new \common\models\User();

        $first_user->username   = 'admin';
        $first_user->email      = 'admin@admin.com';
        $first_user->status     = 1;
        $first_user->generateAuthKey();
        $first_user->setPassword('123456');
        $first_user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
