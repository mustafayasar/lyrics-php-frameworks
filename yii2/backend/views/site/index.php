<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Lyrics Admin Panel';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome to the Panel!</h1>


    </div>

    <div class="body-content" style="text-align: center;">
        <p style="margin-top: 45px;">
            <a class="btn btn-default" href="<?= Url::to(['singer/index'])?>">There are <strong><?= $singers_count ?></strong> singers</a>
            <a class="btn btn-success" href="<?= Url::to(['singer/create'])?>">Create Singer</a>
        </p>
        <p>
            <a class="btn btn-default" href="<?= Url::to(['song/index'])?>">There are <strong><?= $songs_count ?></strong> songs</a>
            <a class="btn btn-success" href="<?= Url::to(['song/create'])?>">Create Song</a>
        </p>

        <p style="padding-top: 35px;">
            <p>There are <?= $search_items_count ?> elastic search items</p>
            <a class="btn btn-primary" href="<?= Url::to(['site/mysql-to-elastic'])?>">Mysql To Elastic</a>
        </p>

        <p style="padding-top: 35px;">
            <a class="btn btn-danger" href="<?= Url::to(['site/flush-redis'])?>">Flush Redis</a>
        </p>


    </div>
</div>
