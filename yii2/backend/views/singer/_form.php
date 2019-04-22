<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Singer */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="singer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'slug') ?>

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