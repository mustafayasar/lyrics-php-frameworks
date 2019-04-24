<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $singers common\models\Singer[] */
/* @var $pages yii\data\Pagination */

$this->title = $title;
?>
<div class="col-lg-8 col-md-10 mx-auto">
    <h1><?= $title ?></h1>
    <div>
        <ul class="pagination">
            <?php foreach ($letters as $key => $val) { ?>
                <li><a href="<?= Url::to(['site/singers', 'i' => $key]) ?>"><?= $val ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <ul class="list-group">
        <?php if (count($singers) > 0) { ?>
            <?php foreach ($singers as $singer) { ?>
                <li class="list-group-item">
                    <a href="<?= Url::to(['site/singer-songs', 'singer_slug' => $singer->slug]) ?>"
                       title="<?= $singer->name ?> Songs"><?= $singer->name ?></a>
                </li>
            <?php } ?>
        <?php } else { ?>
            <p class="text-danger">There is no record in this case.</p>
        <?php } ?>
    </ul>
    <div>
        <nav aria-label="Page navigation">
        <?= LinkPager::widget(['pagination' => $pages, 'maxButtonCount' => 6]) ?>
        </nav>
    </div>
</div>