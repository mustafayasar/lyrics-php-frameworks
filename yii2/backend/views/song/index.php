<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Singer;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $singers common\models\Singer[] */

$this->title = 'Songs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Song', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                'filter'    => Singer::$statuses,
                'value'     => function ($data) {
                    return Singer::$statuses[$data->status];
                }
            ],
            [
                'class'     => 'yii\grid\ActionColumn',
                'template'  => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>