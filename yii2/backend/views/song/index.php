<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Song;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $singers common\models\Singer[] */

$this->title = 'Songs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="pull-right" style="margin-top: -30px;">
        <?= Html::a('Create Song', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'singer_id',
                'filter'    => $singers,
                'value'     => function ($data) {
                    return $data->singer->name;
                }
            ],
            'title',
            'hit',
            [
                'attribute' => 'status',
                'filter'    => Song::$statuses,
                'value'     => function ($data) {
                    return Song::$statuses[$data->status];
                }
            ],
            [
                'class'     => 'yii\grid\ActionColumn',
                'template'  => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>