<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\Pjax;

use common\models\ProjectBlock;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$GLOBALS['blocksCount'] = count($blocks);
$emptyProjectBlock = new ProjectBlock();

Yii::$app->formatter->nullDisplay = '';
$this->registerCss("
.panel {
    margin-bottom: 0px;
}"
);

?>

<div class="project-view">
	<div class="form-view">
		<div class="row">
			<div class="col-sm-8">                
				<h1> 
                   <?php if($model->image): ?>
                                    <a data-toggle="modal" data-target="#imageModal" href="#<?= $model->image ?>">
                                        <span > <img  style="max-height:40px; max-width: 40px;" src="<?= Url::to($model->getImageUrl()) ?>" /></span></a>
					<?php else : ?>
                                    
                                    
							<span class="glyphicon glyphicon-picture" title= "No image" style=" position: relative; top: 5px;color: #899ba3;"></span>
						
                                    <?php endif;?>
                    <?= Html::encode($this->title) ?>
                    <span class="bubbles">
                        <?php if($balloon = $model->getBalloon(Yii::$app->user->identity)): ?>
                            <img title="<?= ucfirst($balloon) ?>" src="<?= Url::to('/images/balloons/' . $balloon . '.png') ?>" />
                        <?php endif; ?>
					</span>
                </h1>
                <?php if ($model->elevator_pitch):?>
                            <label>Elevator Pitch</label></br>
                            
                <span><?= Yii::$app->formatter->asNtext($model->elevator_pitch) ?></span>
                
                <?php endif;?>
			</div>
			<div class="col-sm-4">	
				<p class="pull-right">
                <?= Html::a('Export', ['export/index', 'id' => $model->id], ['class' => 'btn btn-default', 'target' => '_blank']) ?>

                <?php if(Yii::$app->user->projectPermission('update',$model)): ?>
					<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
                <?php if(Yii::$app->user->projectPermission('delete',$model)): ?>
					<?= Html::a('Delete', ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => 'Are you sure you want to delete this item?',
							'method' => 'post',
						],
					]) ?>
                <?php endif; ?>
				</p>
			</div>
		</div>

	</div>
</div>

<p>&nbsp;</p>

<div class="project-grid">
    <div class="row outside-in">
        <div class="grid-type"><img src="/images/outside-in-grid.jpg" alt="outside-in-grid.jpg"></div>

        <?= $this->render('_block', ['name' => 'target', 'project' => $model,'blocks' => $blocks, 'projectBlocks' => $projectBlocks, 'emptyProjectBlock' =>$emptyProjectBlock, 'number' => 1]); ?>

        <?= $this->render('_block', ['name' => 'insight', 'project' => $model,'blocks' => $blocks, 'projectBlocks' => $projectBlocks, 'emptyProjectBlock' =>$emptyProjectBlock, 'number' => 2]); ?>

        <?= $this->render('_block', ['name' => 'alternatives', 'project' => $model,'blocks' => $blocks, 'projectBlocks' => $projectBlocks, 'emptyProjectBlock' =>$emptyProjectBlock, 'number' => 3]); ?>
    </div>

    <div class="row inside-out">
        <div class="grid-type"><img src="/images/inside-out-grid.jpg" alt="inside-out-grid.jpg"></div>

        <?= $this->render('_block', ['name' => 'benefits', 'project' => $model,'blocks' => $blocks, 'projectBlocks' => $projectBlocks, 'emptyProjectBlock' =>$emptyProjectBlock, 'number' => 4]); ?>

        <?= $this->render('_block', ['name' => 'reasons-to-believe', 'project' => $model,'blocks' => $blocks, 'projectBlocks' => $projectBlocks, 'emptyProjectBlock' =>$emptyProjectBlock, 'number' => 5]); ?>

        <?= $this->render('_block', ['name' => 'superiority', 'project' => $model,'blocks' => $blocks, 'projectBlocks' => $projectBlocks, 'emptyProjectBlock' =>$emptyProjectBlock, 'number' => 6]); ?>
    </div>
</div>

<br><br>
<!-- Stakeholder Code HERE, future development ----->

    <div class="project-view">
        <div class="form-view">
            <div class="row">
                <div class="col-sm-4">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'businessUnit.name',
                                'label' => 'Business Unit'
                            ],
                            [
                                'attribute' => 'progress',
                                'value' => function($model) { return $model->progressBar($GLOBALS['blocksCount']); },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'score',
                                'value' => function($model) { return $model->scoreBar; },
                                'format' => 'html'
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-sm-4">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'stage',
                                'value' => function($model) { return $model::$stages[$model->stage]; },
                            ],
                            [
                                'attribute' => 'createdBy.name',
                                'label' => 'Created By'
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>

                <div class="col-sm-4">
                    <table class="table table-striped table-bordered detail-view">
                        <tr><th>Communication Brief</th><td><?= Html::a('Export', ['export/communication-brief', 'id' => $model->id], ['class' => 'btn btn-default', 'target' => '_blank']) ?></td></tr>
                        <tr><th>Discussion Guide</th><td><?= Html::a('Export', ['export/discussion-guide', 'id' => $model->id], ['class' => 'btn btn-default', 'target' => '_blank']) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document"  style="width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?= $model->image ?></h4>
                </div>
                <div class="modal-body text-center">
                    <img src="<?= $model->imageUrl ?>" style="max-width: 100%" />
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="ratingModalLabel"></h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <!-- Documents Modal -->
    <div class="modal fade" id="documentsModal" tabindex="-1" role="dialog" aria-labelledby="documentsModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="documentsModalLabel"></h4>
                </div>
                    <div class="modal-body" style="overflow: auto">
                        <?php Pjax::begin([
                            'id' => 'projectDocumentPjax',
                            'enablePushState' => false
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div class="modal fade" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="commentsModalLabel"></h4>
                </div>
                <div class="modal-body" style="overflow: auto">
                    <?php Pjax::begin(['id' => 'projectCommentPjax','enablePushState' => false]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php $this->beginJs(); ?>
<script type="text/javascript">
    /* equal height function */
    equalheight = function(container){
        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0;
        $(container).each(function() {
            $el = $(this);
            $($el).height('auto')
            topPostion = $el.position().top;

            if (currentRowStart != topPostion) {
                for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
                rowDivs.length = 0; // empty the array
                currentRowStart = topPostion;
                currentTallest = $el.height();
                rowDivs.push($el);
            } else {
                rowDivs.push($el);
                currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
            }
            for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }

    function openRating(link){
        $('#ratingModal').find('.modal-title').text($(link).data('title')+" Rating");
        $('#ratingModal').find('.modal-body').text('Loading...');
        $('#ratingModal').find('.modal-body').load(encodeURI(link.href));
        $('#ratingModal').modal();
        return false;
    }
    var currentBoxButton;

    function openDocuments(link){
        currentBoxButton = link;
        $('#documentsModal').find('.modal-title').text($(link).data('title')+" Documents");
        $('#projectDocumentPjax').text('Loading...');
        $('#projectDocumentPjax').load(encodeURI(link.href));
        $('#documentsModal').modal();
        return false;
    }
    function openComments(link){
        currentBoxButton = link;
        $('#commentsModal').find('.modal-title').text($(link).data('title')+" Comments");
        $('#projectCommentPjax').text('Loading...');
        $('#projectCommentPjax').load(encodeURI(link.href));
        $('#commentsModal').modal();
        return false;
    }
     function changeScore(score, comments){
         if(score.options[score.selectedIndex].value == '') {
             $(comments).val('');
             $(comments).prop('readonly', true);
         }else{
             $(comments).prop('readonly', false);
         }
     }
</script>
<?php $this->endJs(static::POS_HEAD); ?>

<?php $this->beginJs(); ?>
    <script type="text/javascript">
        $('[data-toggle="tooltip"]').tooltip();
       // makeKeyStakeholder();
        equalheight(".row:not(.letter-only-row) .col-st-7:not(.has-letter-left) .panel");
        equalheight(".project-grid .row .panel");
        equalheight(".project-grid .row .panel-heading");
        $(window).resize(function(){
            equalheight(".row:not(.letter-only-row) .col-st-7:not(.has-letter-left) .panel");
            equalheight(".project-grid .row .panel");
            equalheight(".project-grid .row .panel-heading");
        });


        $(document).on('pjax:beforeSend', function(event, xhr, options) {
            $(event.target).html('<i>Loading...</i>');
        });
        $(document).on('pjax:success', function(event, data, status, xhr, options) {
            var n = $(event.target).find('tbody tr[data-key]').length;
            if(n>0) {
                $(currentBoxButton).find('span').css('color','green');
            }else{
                $(currentBoxButton).find('span').css('color','');
            }
        });
        $(document).on('pjax:error', function(event, xhr, textStatus, error, options) {
            options.success(xhr.responseText, status, xhr);
            return false;
        });

    </script>
<?php $this->endJs(static::POS_READY); ?>