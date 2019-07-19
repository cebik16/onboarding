<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\widgets\Select2;

use common\models\VpTree;
use common\models\User;

$this->registerJsFile(
    '@web/plugins/bootstrap-treeview/js/bootstrap-treeview.js',
    ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]
);
$this->registerCssFile(
    '@web/plugins/bootstrap-treeview/css/bootstrap-treeview.css',
    ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]
);
$this->registerCssFile(
    '@web/css/tree.css',
    ['depends' => [\yii\bootstrap\BootstrapPluginAsset::className()]]
);

$project = $model->project;
$businessUnit = VpTree::findOne($model->project->vp_tree_id);

$viewUsers = ArrayHelper::map(User::find()
    ->where(['role' => User::ROLE_REGULAR,'status' =>User::STATUS_ACTIVE])
    ->andWhere(['<>','id',Yii::$app->user->id])
    ->orderBy(['first_name' => SORT_ASC,'last_name' => SORT_ASC])
    ->all(), 'id', 'name');

$editUsers = ArrayHelper::map(User::find()
    ->where(['status' =>User::STATUS_ACTIVE])
    ->andWhere( ['<=','role', User::ROLE_COACH])
    ->andWhere(['<>','id',Yii::$app->user->id])
    ->orderBy(['first_name' => SORT_ASC,'last_name' => SORT_ASC])
    ->all(), 'id', 'name');
?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'projectform',
        'options' => ['enctype' => 'multipart/form-data']
    ]
); ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model->project, 'name')->textInput(['maxlength' => true]) ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model->project, 'vp_tree_id', [
                    'template' => "{label}\n{input}\n{hint}\n &nbsp; <span id='businessUnit'>".($businessUnit?$businessUnit->name:null)."</span>\n{error}"
                ])->hiddenInput();?>
            </div>
            <div class="col-md-6"><button type="button" class="btn" style="background-color: #CF1971; color: white;" data-toggle="modal" data-target="#vpTreeModal">VP Tree</button></div>
        </div>
        <?= $form->field($model->project, 'elevator_pitch')->textArea(['maxlength' => true]) ?>
        <?= $form->field($model->project, 'stage')->dropDownlist( $project::$stages ) ?>
        <?php if (Yii::$app->user->isAdmin()):?>
            <?= $form->field($model->project, 'created_by')->dropDownlist( $usersList,['prompt'=>'Select User']) ?>
        <?php else :?>
            <?= $form->field($model->project, 'created_by')->dropDownlist( $usersList,['disabled' => 'disabled']) ?>
        <?php endif;?>
        <?= $form->field($model, 'delete_image')->hiddenInput()->label(false) ?>

        <?php $this->beginHtml(); ?>
        Maximum allowed size for uploaded file: <b><?= ini_get('upload_max_filesize'); ?>B</b>
        <br />
        Accepted file types:
        <b><?= implode(', ', Yii::$app->params['project']['image']['extensions']) ?></b>
        <br />
        If the image is too large it will be resided to a maximum
        <b><?= Yii::$app->params['project']['image']['width'] ?>x<?= Yii::$app->params['project']['image']['height'] ?></b>px
        <?php $imageHint = $this->endHtml(); ?>

        <?= $form->field($model, 'image')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showRemove' => false,
                'initialPreview' => $model->project->image ? [Html::img($model->project->imageUrl, ['title' => $model->project->image])] : [],
                'initialCaption' => $model->project->image ? $model->project->image : null,
                'allowedFileExtensions' => Yii::$app->params['project']['image']['extensions'],
                'showUpload' => false,
                'maxFileSize' => trim(ini_get('upload_max_filesize'), 'M') * 1024,
            ],
            'pluginEvents' => [
                'filecleared' => 'function(event) { $("#projectform-delete_image").val("1"); }',
                'fileloaded' => 'function(event, file, previewId, index, reader) { $("#projectform-delete_image").val("0"); }',
            ],
        ])->hint($imageHint);
        ?>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">User Permissions</div>
            <div class="panel-body">
                <?= $form->field($model, 'userPermissions[view]')->widget(Select2::classname(), [
                    'data' => $viewUsers,
                    'options' => ['placeholder' => 'Select users ...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 10
                    ],
                ])->label('View Permissions') ?>

                <?= $form->field($model, 'userPermissions[edit]')->widget(Select2::classname(), [
                    'data' => $editUsers,
                    'options' => ['placeholder' => 'Select users ...', 'multiple' => true],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 10
                    ],
                ])->label('Edit Permissions') ?>
            </div>
        </div>
    </div>
</div>
<hr class="separator">
<div class=" text-center">
    <?= Html::submitButton($model->project->isNewRecord ? 'Create' : 'Update', ['class' => $model->project->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<!-- VP Tree Modal -->
<div class="modal fade" id="vpTreeModal" tabindex="-1" role="dialog" aria-labelledby="vpTreeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="vpTreeModalLabel">VP Tree</h4>
            </div>
            <div class="modal-body tree" style="overflow: auto">

            </div>
        </div>
    </div>
</div>

<?php $this->beginJs(); ?>
<script type="text/javascript">

    var toggleTree = true, tree, nodes = new Array;

    function getTree(tree, level) {
        var newTree = new Array();
        var elem, newElem, children, node;
        for (var id in  tree) {
            if (tree.hasOwnProperty(id)) {
                elem = tree[id];
                node = document.createElement("span");
                $(node).text(elem.name);
                $(node).addClass('label');
                $(node).attr('data-id',id);
                switch(level) {
                    case 0:
                        $(node).addClass('tree-level-0');
                        break;
                    case 1:
                        $(node).addClass('tree-level-1');
                        break;
                    case 2:
                        $(node).addClass('tree-level-2');
                        break;
                    case 3:
                        $(node).addClass('tree-level-3');
                        break;
                    default:
                        $(node).addClass('tree-level');
                }

                newElem = {text: node.outerHTML };
                if (elem.hasOwnProperty('children')) {
                    children = getTree(elem.children,level+1);
                    newElem.nodes = children;
                }
                newTree.push(newElem)
            }
        }
        return newTree;
    }

</script>
<?php $this->endJs(static::POS_HEAD); ?>

<?php $this->beginJs(); ?>
<script type="text/javascript">
    var treeData = <?= json_encode($tree) ?>;
    $('.tree').treeview({
        data: getTree(treeData,0),
        levels: 4,
        selectedBackColor: "#F6E3CE",
        expandIcon: "glyphicon glyphicon-chevron-down",
        collapseIcon: "glyphicon glyphicon-chevron-up",
        onNodeSelected: function(event, node) {
            console.log(node);
            $("#project-vp_tree_id").val($(node.text).data('id'));
            $("#businessUnit").text($(node.text).text());
            $('#<?= $form->id ?>').yiiActiveForm('validateAttribute', 'project-vp_tree_id');
            if(toggleTree){
                $("#vpTreeModal").modal("toggle")
            }
        },
        onNodeUnselected : function(event, node) {
            $("#project-vp_tree_id").val(null);
            $("#businessUnit").text(null);
            $('#<?= $form->id ?>').yiiActiveForm('validateAttribute', 'project-vp_tree_id');
        }
    });
    tree = $('.tree').treeview(true);
    nodes = tree.getEnabled();
    for(var i=0;i<nodes.length;i++){
        nodes[i].id = $(nodes[i].text).data('id')
    }

    <?php if($businessUnit): ?>
    toggleTree = false;
    for(i=0;i<nodes.length;i++){
        if(nodes[i].id == <?= $businessUnit->id ?>){
            tree.selectNode(nodes[i]);
            break;
        }
    }
    toggleTree = true;
    <?php endif; ?>
</script>
<?php $this->endJs(static::POS_READY); ?>

