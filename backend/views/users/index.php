<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\GridView as GridView;
    
    use yii\helpers\VarDumper;
    use yii\web\View;
    
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$GLOBALS['searchModel'] = $searchModel;

?>
    <div class="user-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                'first_name',
                'last_name',
                'email:email',
                [
                    'attribute' => 'role',
                    'filter' => $searchModel::$roles,
                    'value' => function ($model, $key, $index, $widget) {  return $GLOBALS['searchModel']::$roles[$model->role]; },
                ],
                [
                    'attribute' => 'status',
                    'filter' => $searchModel::$statuses,
                    'value' => function ($model, $key, $index, $widget) {  return $GLOBALS['searchModel']::$statuses[$model->status]; },
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                    'filter' => false
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {approve} {delete}',
                    'buttons' => [
                        'Activate' => function ($url, $model, $key) {
                            if($model->status === $model::STATUS_INACTIVE) {
                                return Html::a('<i class="glyphicon glyphicon-check"></i>', ['change-status', 'id'=>$model->id], [
                                    'title' => 'Activate',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to Approve this user?',
                                        'method' => 'post',
                                    ]
                                ]);
                            }
                            return null;
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['validate-delete', 'id'=>$model->id], ['title' => 'Delete', 'onclick' => 'return validateDelete(this);']);
                        }
                    ],
                ],
            ],
        ]);
        ?>
    </div>

<?php
$csrfParams =  Yii::$app->request->csrfParam;
$scsrfToken = Yii::$app->request->csrfToken;
$url_delete = Url::to(['delete'], true);

$js = <<<JS
function validateDelete(link){
    
    var request = $.ajax({
        url: link.href,
        type: "get",
        dataType: "json"
    })
    .done(function (response, textStatus, jqXHR) {
        let r;
        if(response.hasProjects){
            r = confirm("The user has projects created. All his work will be lost. Are you sure you want to delete ?");
        }else{
            r = confirm("Are you sure you want to delete ?");
        }
        
        let action = new URL('$url_delete');
        action.searchParams.append('id', response.id);
        
        const postData = {"$csrfParams": "$scsrfToken"};
        
        if (r === true) {
            $.ajax({
                url: action.href,
                type: "post",
                dataType: "json"
            });
        }
        return false;
    })
    .fail(function (jqXHR, textStatus, errorThrown){
        alert(textStatus + ': '+ errorThrown);
        return false;
    });
    return false;
}
JS;
$this->registerJs($js, View::POS_END);
?>