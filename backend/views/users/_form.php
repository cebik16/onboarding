<?php

use yii\helpers\Html;
    use yii\helpers\VarDumper;
    use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$user = $model->user;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($user, 'username')->textInput() ?>
    <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'role')->dropDownList($user::$roles) ?>
    <?= $form->field($user, 'status')->dropDownList($user::$statuses) ?>

    <?= $form->field($model, 'password')->passwordInput(['value' => '', 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'confirm_password')->passwordInput(['value' => '', 'autocomplete' => 'off'])->hint($model->user->isNewRecord?null:'Leave the passwords fields empty if you dont want to change it') ?>
    <?php if(isset($isUpdate) && $isUpdate) echo Html::img($user->avatarUrl, ['width' => 'auto', 'height' => 100]) ?>
    <?= $form->field($model, 'avatar')->fileInput(['accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->user->isNewRecord ? 'Create' : 'Update', ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
