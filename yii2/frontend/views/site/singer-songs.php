<?php
use yii\helpers\Url;
use common\helpers\SiteHelper;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $singer common\models\Singer */
/* @var $songs common\models\Song[] */
/* @var $pages yii\data\Pagination */

$this->title = $singer->name. ' Songs';
?>
<div class="col-lg-8 col-md-10 mx-auto">
    <?php if (count($songs) > 0) { ?>
        <?php foreach ($songs as $song) { ?>
            <div class="post-preview">
                <?php $song_url = Url::to(['site/song-view', 'singer_slug' => $song->singer->slug, 'song_slug' => $song->slug]) ?>

                <a href="<?= $song_url ?>" title="<?= $song->title ?> Lyrics - <?= $song->singer->name ?>">
                    <h2 class="post-title">
                        <?= $song->title ?>
                    </h2>
                </a>
                <p>
                    <?= SiteHelper::getPreviewLyrics($song->lyrics) ?>

                    <a class="more" href="<?= $song_url ?>" title="<?= $song->title ?> Lyrics - <?= $song->singer->name ?>">Read More <i class="fas fa-angle-double-right"></i></a>
                </p>
                <p class="post-meta">Posted on <?= SiteHelper::getPostedDate($song->created_at) ?></p>
            </div>
        <?php } ?>

        <div>
            <?= LinkPager::widget(['pagination' => $pages, 'maxButtonCount' => 6]) ?>
        </div>
    <?php } else { ?>
        <p class="text-danger">There is no song.</p>
    <?php } ?>
</div>