<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<ul class="nav nav-tabs">
    <li role="presentation" <?= $action=='index'?'class="active"':null ?>><a href="<?= $action=='index'?'#':(Url::to(['index'])) ?>">My Projects</a></li>
    <li role="presentation" <?= $action=='viewable'?'class="active"':null ?>><a href="<?= $action=='viewable'?'#':(Url::to(['viewable'])) ?>">Viewable Projects</a></li>
    <li role="presentation" <?= $action=='editable'?'class="active"':null ?>><a href="<?= $action=='editable'?'#':(Url::to(['editable'])) ?>">Editable Projects</a></li>
    <li role="presentation" <?= $action=='all'?'class="active"':null ?>><a href="<?= $action=='all'?'#':(Url::to(['all'])) ?>">All Projects</a></li>
    <li role="presentation" <?= $action=='create'?'class="active"':null ?>><a href="<?= $action=='create'?'#':(Url::to(['create'])) ?>">Create Project</a></li>
</ul>