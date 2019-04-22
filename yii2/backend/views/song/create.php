<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Song */
/* @var $singers common\models\Singer[] */

$this->title = 'Create Song';
$this->params['breadcrumbs'][] = ['label' => 'Songs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'     => $model,
        'singers'   => $singers
    ]) ?>

</div>