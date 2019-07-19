<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = 'Update Project: ' . $model->project->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project->name, 'url' => ['view', 'id' => $model->project->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="project-update">
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
