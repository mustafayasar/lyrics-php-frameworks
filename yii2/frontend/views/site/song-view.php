<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\SiteHelper;
/* @var $this yii\web\View */
/* @var $song common\models\Song */

$this->title = $song->singer->name.' - '.$song->title. ' Lyrics';
?>
<div class="col-lg-8 col-md-10 mx-auto">
    <div class="post-preview">
        <p>
            <?= $song->lyrics ?>
        </p>
        <p class="post-meta">Posted on <?= SiteHelper::getPostedDate($song->created_at) ?></p>
        <p class="post-meta">Viewed <?= $song->hit ?> times</p>
    </div>
</div>