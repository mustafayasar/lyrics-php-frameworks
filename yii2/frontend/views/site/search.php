<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\SiteHelper;
/* @var $this yii\web\View */
/* @var $result common\models\ElasticItem */

$this->title = 'Search for '.$q;
?>
<div class="col-lg-8 col-md-10 mx-auto">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (count($result) > 0) { ?>
        <?php foreach ($result as $item) {
            $url = Url::to(unserialize($item->url))?>
            <?php if ($item->type == 'song') { ?>
                <div class="post-preview">
                    <a href="<?= $url ?>" title="<?= $item->title ?>">
                        <h2 class="post-title">
                            <?= $item->title ?>
                        </h2>
                    </a>
                    <p>
                        <?= SiteHelper::getPreviewLyrics($item->content) ?>

                        <a class="more" href="<?= $url ?>" title="<?= $item->title ?>">Read More <i class="fas fa-angle-double-right"></i></a>
                    </p>
                </div>
                <hr>
            <?php } elseif ($item->type == 'singer') { ?>
                <div class="post-preview">
                    <a href="<?= $url ?>" title="<?= $item->title ?>">
                        <h2 class="post-title">
                            <?= $item->title ?> (Singer)
                        </h2>
                    </a>
                </div>
                <hr>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
        <p class="text-danger">There is no result.</p>
    <?php } ?>
</div>