<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->registerJsFile('@web/plugins/tinymce/tinymce.min.js', ['position' => $this::POS_END]);
$this->registerJsFile('@web/plugins/imageMapResizer.min.js', ['depends' => 'yii\bootstrap\BootstrapPluginAsset']);

Yii::$app->formatter->nullDisplay = '';
$color = Html::encode($block->color);

$formId = 'workspaceForm';
?>
<div class="project-view">
    <div class="form-view">
        <div class="row">
            <div class="col-sm-8">
                <h1>
                    <?php if($project->image): ?>
                        <a data-toggle="modal" data-target="#imageModal" href="#<?= $project->image ?>">
                            <span > <img  style="max-height:40px; max-width: 40px;" src="<?= Url::to($project->getImageUrl()) ?>" />
                        </a>
                    <?php else : ?>
                        <span class="glyphicon glyphicon-picture" title= "No image" style=" position: relative; top: 5px;color: #899ba3;"></span>
                    <?php endif; ?>
                                             
                    <a href="<?= Url::to(['view', 'id'=>$project->id]) ?>"><?= Html::encode($project->name) ?></a>
                    <span class="bubbles">
                        <?php if($balloon = $project->getBalloon(Yii::$app->user->identity)): ?>
                            <img title="<?= ucfirst($balloon) ?>" src="<?= Url::to('/images/balloons/' . $balloon . '.png') ?>" />
                        <?php endif; ?>
					</span>
                </h1>
                <?php if ($project->elevator_pitch):?>
                    <label>Elevator Pitch</label>
                    </br>
                    <span><?= Yii::$app->formatter->asNtext($project->elevator_pitch) ?></span>
                <?php endif;?>
            </div>
            <div class="col-sm-4">
                <p class="pull-right">
                    <?= Html::a('<i class="fa fa-arrow-left"></i> Go Back', ['default/view','id' => $project->id], ['class'=>'btn btn-primary']) ?>
                    <button class="btn btn-success" onclick="window.onbeforeunload = null; $('#<?= $formId ?>').submit();">Save</button>
                       

                </p>
            </div>
        </div>
        <hr class="separator">
        <div class="row">
            <div class="col-md-4">
                <?php $form = ActiveForm::begin([
                    'id' => $formId
                ]); ?>
                <div class="box">
                    <h1 style="background-color: <?= Html::encode($color) ?>;"><?= Html::encode($block->name) ?></h1>
                    <div class="workspace-box" >
                    <p class="workspace-paragraph"><strong><?= Html::encode($block->description) ?></strong></p>
                    
                    <?= $form->field($model, 'content')->textArea(['placeholder' => Yii::$app->formatter->asHtml($block->placeholder)])->label(false) ?>
                                            
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-8">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#progress-map">Progress Map</a></li>
                    <li><a data-toggle="tab" href="#instructions">Instructions</a></li>
                    <li><a data-toggle="tab" href="#video">Video</a></li>
                </ul>

                <div class="tab-content">
                    <div id="progress-map" class="tab-pane fade in text-center active ">
                        <p>&nbsp;</p>
                        <img title="Progress Map" src="<?= Yii::getAlias('@web/images/progress-map.png') ?>" alt="progress-map.png" usemap="#progressMap">
                    </div>
                    <div id="instructions" class="tab-pane fade">
                        <p>&nbsp;</p>
                        <?= Yii::$app->formatter->asHtml($block->instructions); ?>
                    </div>
                    <div id="video" class="tab-pane fade text-center">
                        <p>&nbsp;</p>
                        <?php if ($block->video): ?>
                            <iframe width="640" height="360" frameborder="0" allowfullscreen src="https://www.youtube.com/embed/<?= Html::encode($block->video) ?>?rel=0&amp;showinfo=0&amp;enablejsapi=1"></iframe>
                        <?php else: ?>
                            <i>Video unavailable</i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<map name="progressMap" id="progressMap">
    <area title="Go to <?= Html::encode($blocks['target']->name) ?>" href="<?= Url::to(['workspace', 'projectId'=>$project->id, 'blockId' =>  $blocks['target']->id]) ?>" shape="poly" coords="2,52,108,38,106,192,1,181" />
    <area title="Go to <?= Html::encode($blocks['insight']->name) ?>" href="<?= Url::to(['workspace', 'projectId'=>$project->id, 'blockId' =>  $blocks['insight']->id]) ?>" shape="poly" coords="113,37,234,22,232,205,110,191" />
    <area title="Go to <?= Html::encode($blocks['alternatives']->name) ?>" href="<?= Url::to(['workspace', 'projectId'=>$project->id, 'blockId' =>  $blocks['alternatives']->id]) ?>" shape="poly" coords="238,20,380,2,379,222,239,204" />
    <area title="Go to <?= Html::encode($blocks['benefits']->name) ?>" href="<?= Url::to(['workspace', 'projectId'=>$project->id, 'blockId' =>  $blocks['benefits']->id]) ?>" shape="poly" coords="2,185,106,196,107,338,1,306" />
    <area title="Go to <?= Html::encode($blocks['reasons-to-believe']->name) ?>" href="<?= Url::to(['workspace', 'projectId'=>$project->id, 'blockId' =>  $blocks['reasons-to-believe']->id]) ?>" shape="poly" coords="110,195,235,210,233,375,112,338" />
    <area title="Go to <?= Html::encode($blocks['superiority']->name) ?>" href="<?= Url::to(['workspace', 'projectId'=>$project->id, 'blockId' =>  $blocks['superiority']->id]) ?>" shape="poly" coords="238,208,382,225,380,421,236,376" />
</map>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel">
    <div class="modal-dialog" role="document"  style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="imageModalLabel"><?= Html::encode($project->image) ?></h4>
            </div>
            <div class="modal-body text-center">
                <img src="<?= $project->imageUrl ?>" style="max-width: 100%" />
            </div>
        </div>
    </div>
</div>


<?php $this->beginJs(); ?>
    <script type="text/javascript">
        var formInitialData = $("#<?= $form->id; ?>").serialize();

        $("#<?= $form->id; ?>").on('focusout', function (event) {
            formChanged = (formInitialData != $(this).serialize());
            return checkChanged();
        });

        $('map').imageMapResize();
    </script>
<?php $this->endJs(static::POS_READY); ?>

<?php $this->beginJs(); ?>
    <script type="text/javascript">
        var options = tinymceOptions;
        options.selector = "#projectblock-content";
        tinymce.init(options);
    </script>
<?php $this->endJs(static::POS_READY); ?>

<?php $this->beginJs(); ?>
    <script type="text/javascript">
        var formChanged = false;
        var editorChanged = false;
        var tinymceOptions = {
            height: 290,
            toolbar: ['bold italic underline | alignleft aligncenter alignright | bullist numlist'],
            plugins: ['placeholder', 'charcount', 'paste'],
            menubar: false,
//            forced_root_block: false,
            content_css: '<?= Yii::getAlias('@web/css/tinymce.css?v=' . time()) ?>',

            setup: function (ed) {
                tinymceSetup(ed);
            },
            valid_elements: 'i/em,span,strong/b,br,ul,ol,li,div,p,u',
            extended_valid_elements: 'span[style],div[style],p[style]',
            valid_styles: {
                '*': ['text-decoration', 'text-align']
            }
        }
        function tinymceSetup(ed) {
            ed.on('focus', function (event) {
                if (ed.getContent() == '') {
                    var defaultValue = $(ed.getElement()).data('default');
                    if (typeof defaultValue !== "undefined" && defaultValue != '') {
                        ed.setContent(defaultValue.replace(/(\r\n|\n|\r)/gm, "<br />"));
                    }
                }
            });
            ed.on('change', function (event) {
                editorChanged = (ed.getElement().value.trim() != ed.getContent().trim());
                return checkChanged();
            });
            ed.on('keyup', function (event) {
                editorChanged = (ed.getElement().value.trim() != ed.getContent().trim());
                return checkChanged();
            });
        }

        function checkChanged() {
            if (formChanged || editorChanged) {
                window.onbeforeunload = function () {
                    return "You have unsaved data"
                };
            } else {
                window.onbeforeunload = null;
            }
            return;
        }
    </script>
<?php $this->endJs(static::POS_HEAD); ?>