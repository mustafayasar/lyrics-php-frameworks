<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Song;
/* @var $this yii\web\View */
/* @var $model common\models\Song */
/* @var $singers common\models\Singer[] */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="singer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'singer_id')->dropDownList($singers, ['prompt' => '---Select Singer---']) ?>

    <?= $form->field($model, 'title') ?>

    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'slug')->hiddenInput()->label(false) ?>
    <?php } else { ?>
        <?= $form->field($model, 'slug') ?>
    <?php } ?>

    <?= $form->field($model, 'lyrics')->textarea(['rows' => 16]) ?>

    <?php if (!$model->isNewRecord) { ?>
        <?= $form->field($model, 'hit')->textInput(['readonly' => 'readonly']) ?>
        <?= $form->field($model, 'status')->dropDownList(Song::$statuses) ?>
    <?php } ?>

    <div class="form-group">
        <?php if ($model->isNewRecord) { ?>
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        <?php } else { ?>
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>