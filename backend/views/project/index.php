<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\widgets\KartikGridView as GridView;

use common\models\User;
use common\models\Block;
use common\models\VpTree;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;

$GLOBALS['blocksCount'] = Block::find()->count();

?>
<div class="project-index">
<!--    --><?php //echo $this->render('_tabs', ['action' => $this->context->action->id]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            [
                'attribute' => 'name',
                'value' => function($model, $key, $index, $widget) {
                    $name = Html::encode($model->name);
                    $html = $name;

                    $balloon = $model->getBalloon(Yii::$app->user->identity);
                    if($balloon) {
                        $html .= ' <img title="' . ucfirst($balloon) . '" src="' . \yii\helpers\Url::to('/images/balloons/' . $balloon . '.png') . '" />';
                    }
                    return $html;
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'businessUnit',
                'value' => 'businessUnit.name',
                'format' => 'text',
                'filter' => false
//                'filterType' => GridView::FILTER_SELECT2,
//                'filter' => ArrayHelper::map(VpTree::find()
//                    ->orderBy(['name' => SORT_ASC])
//                    ->all(), 'id', 'name'),
//                'filterWidgetOptions' => [
//                    'pluginOptions' => ['allowClear' => true, 'dropdownAutoWidth' => false],
//                ],
//                'filterInputOptions' => [
//                    'placeholder' => 'Select Business Unit'
//                ],
            ],
            [
                'attribute' => 'progress',
                'filter' => false,
                'value' => function($model, $key, $index, $widget) { return $model->progressBar($GLOBALS['blocksCount']); },
                'format' => 'html',
            ],
            [
                'attribute' => 'score',
                'filter' => false,
                'value' => function($model, $key, $index, $widget) { return $model->scoreBar; },
                'format' => 'html',
            ],
            [
                'attribute' => 'created_by',
                'filter' => false,
                'value' => 'createdBy.name',

//                'filterType' => GridView::FILTER_SELECT2,
//                'filter' => ArrayHelper::map(User::find()
//                    ->where(['status' => 1])
//                    ->orderBy(['first_name' => SORT_ASC,'last_name' => SORT_ASC])
//                    ->all(), 'id', 'name'),
//                'filterWidgetOptions' => [
//                    'pluginOptions' => ['allowClear' => true, 'dropdownAutoWidth' => false],
//                ],
//                'filterInputOptions' => [
//                        'placeholder' => 'Select User'
//                ],
            ],
            [
                'attribute' => 'created_at',
                'filter' => false,
                'format' => 'date',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' =>  function ($model, $key, $index) {
                        return Yii::$app->user->projectPermission('update',$model);
                    },
                    'delete' =>  function ($model, $key, $index) {
                        return Yii::$app->user->projectPermission('delete',$model);
                    }
                ]
            ],
        ],
    ]); ?>
</div>

<?php $this->beginJs(); ?>
    <script type="text/javascript">

    </script>
<?php $this->endJs(static::POS_READY); ?>