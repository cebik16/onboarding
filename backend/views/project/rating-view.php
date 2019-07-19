<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $project->name. ' Rating';

?>

<?php if(!$model->score && !$model->comments): ?>
    <i>No rating available</i>
<?php else: ?>
    <div class="rating-view">
        <div class="form-view">
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'score',
                        'comments:ntext'
                    ],
                ]) ?>
            </div>
        </div>
    </div>
<?php endif; ?>