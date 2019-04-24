<?php

namespace common\tests\unit\models;

use common\models\Singer;
use common\models\Song;
use PHPUnit\Framework\TestResult;
use Yii;
use common\models\LoginForm;
use common\fixtures\UserFixture;

/**
 * ModelBasicTests
 */
class ModelBasicTests extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function testLoginNoUser()
    {
        expect('singers should be more than 100', Singer::find()->count())->greaterThan(100);
        expect('find song test', Song::findOne(269))->contains("A Cowboy's Toughest Ride");
    }

}
