<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Singer */

$this->title = 'Create Singer';
$this->params['breadcrumbs'][] = ['label' => 'Singers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>