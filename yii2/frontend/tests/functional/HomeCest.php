<?php
namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use yii\helpers\Url;

class HomeCest
{
    public function checkHome(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('Lyrics');

        $I->seeLink('Singers');
        $I->click('Singers');

        $I->see('Hit Singers');
    }
}
