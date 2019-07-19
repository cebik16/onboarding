<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\widgets\Select2;

use common\models\VpTree;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="rating-form">

    <?php $form = ActiveForm::begin([
        'id' => 'ratingForm',
    ])?>


    <?= $form->field($model, 'score')->dropDownlist( array_combine(range(1,10), range(1,10)), [
        'prompt' => '',
        'onchange' => "return changeScore(this,document.getElementById('projectblock-comments'));"
    ])?>
    <?= $form->field($model, 'comments')->textArea(['maxlength' => true, 'readonly' => !(bool)$model->score])->hint('To add comments, select a Score first.') ?>

    <div class="form-group">
        <?= Html::submitButton($model->project->isNewRecord ? 'Create' : 'Update', ['class' => $model->project->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>