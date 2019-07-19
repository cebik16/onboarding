<?php
    use yii\helpers\Html;
    use yii\helpers\Url;

    $block = $blocks[$name];
    $projectBlock = isset($projectBlocks[$name])?$projectBlocks[$name]:$emptyProjectBlock;
    $color = Html::encode($block['object']->color);
?>
<div class="col-sm-4 <?= $block['object']->slug ?>-box">
    <div class="panel panel-default shadow" style="border: 2px solid <?= $color ?>">
        <div class="panel-heading">
            <div class="letter"><span class="number" style="color: <?= $color ?>;"><?= $number ?></span> <p style="color: <?= $color ?>;"><?= $block['object']->name[0] ?> <span><?= Html::encode($block['object']->name) ?></span></p></div>
            <h3 class="panel-title"><?= Html::encode($block['object']->title) ?>
                <span class="icons">
                    <a
                        style="color: <?= $color ?>;"
                        title="Rating"
                        data-title="<?= Html::encode($block['object']->name) ?>"
                        href="<?= Url::to(['default/rating', 'projectId' => $project->id, 'blockId' => $block['object']->id]) ?>"
                        onclick="return openRating(this);"
                    >
                        <span class="glyphicon glyphicon-star" style="<?= $projectBlock->score?'color: green;':null ?>"></span>
                    </a>
                    <a
                        style="color: <?= $color ?>;"
                        title="Documents"
                        data-title="<?= Html::encode($block['object']->name) ?>"
                        href="<?= Url::to(['documents/index', 'projectId' => $project->id, 'blockId' => $block['object']->id, 'checkPostRequest' => 1]) ?>"
                        onclick="return openDocuments(this);"
                    >
                        <span class="glyphicon glyphicon-file" style="<?= $block['documentsNo']?'color: green;':null ?>"></span>
                    </a>
                    <a
                        style="color: <?= $color ?>;"
                        title="Comments"
                        data-title="<?= Html::encode($block['object']->name) ?>"
                        href="<?= Url::to(['comments/index', 'projectId' => $project->id, 'blockId' => $block['object']->id, 'checkPostRequest' => 1]) ?>"
                        onclick="return openComments(this);"
                    >
                        <span class="glyphicon glyphicon-comment" style="<?= $block['commentsNo']?'color: green;':null ?>"></span>
                    </a>
                </span>
            </h3>
        </div>
        <div class="panel-body"
        <?php if(Yii::$app->user->projectPermission('edit',$project)): ?>
            style="cursor: pointer;" onclick="window.location.href='<?= Url::to(['/projects/default/workspace', 'projectId' => $project->id, 'blockId' => $block['object']->id]) ?>'"
        <?php endif; ?>
        >
            <h4><?= Html::encode($block['object']->description) ?></h4>
            <?= Yii::$app->formatter->asHtml($projectBlock->content) ?>
        </div>
    </div>
</div>