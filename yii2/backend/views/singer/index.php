<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Singer;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Singers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="pull-right" style="margin-top: -30px;">
        <?= Html::a('Create Singer', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <div class="clearfix"></div>

    <?= GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'columns' => [
            'id',
            'name',
            'slug',
            'hit',
            [
                'attribute' => 'status',
                'filter'    => Singer::$statuses,
                'value'     => function ($data) {
                    return Singer::$statuses[$data->status];
                }
            ],
            [
                'class'     => 'yii\grid\ActionColumn',
                'template'  => '{songs} {update} {delete}',
                'buttons'   => [
                    'songs' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', ['song/index', 'SongSearch[singer_id]' => $model->id]) ;
                    },
                ]
            ],
        ],
    ]); ?>
</div>