<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = 'Create Project';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="project-create">
    <div class="form-view">
        <div class="row">
            <div class="col-sm-12">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
        <hr class="separator">
        <?= $this->render('_form', [
            'model' => $model,
            'usersList'=>$usersList,
            'tree' => $tree
        ]) ?>
    </div>
</div>
