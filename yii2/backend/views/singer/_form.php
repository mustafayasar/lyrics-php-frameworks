<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Singer;
/* @var $this yii\web\View */
/* @var $model common\models\Singer */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="singer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?php if (!$model->isNewRecord) { ?>
        <?= $form->field($model, 'slug') ?>
        <?= $form->field($model, 'hit')->textInput(['disabled' => 'disabled']) ?>
        <?= $form->field($model, 'status')->dropDownList(Singer::$statuses) ?>
    <?php } else { ?>
        <?= $form->field($model, 'slug')->hiddenInput()->label(false) ?>
    <?php } ?>

    <div class="form-group">
        <?php
            if ($model->isNewRecord) {
                echo Html::submitButton('Create', ['class' => 'btn btn-success']);
            } else {
                echo Html::submitButton('Update', ['class' => 'btn btn-primary']);
            }
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>