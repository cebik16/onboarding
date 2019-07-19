<?php

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to('@web/', true);
$parts = parse_url($url);
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $parts['user'] = $_SERVER['PHP_AUTH_USER'];
    $parts['pass'] = $_SERVER['PHP_AUTH_PW'];
}
$url = http_build_url($parts);
?>
<html>
    <head>
        <title></title>
        <style type="text/css">
        </style>
    </head>
    <body>
    <htmlpageheader name="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="font-size: 21px; font-family: Arial; font-weight: bold;" align="center">
                    <?= $model->name ?>
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <htmlpagefooter name="footer">
        <table width="100%"  >
            <tr>
                <td style="font-size:12px; font-family: Arial;" align="center">
                    Business Unit: <?= $model->businessUnit->name ?> | Project Stage: <?= $model::$stages[$model->stage] ?> | Created By: <?= $model->createdBy->name ?>
                </td>
            </tr>
        </table>
    </htmlpagefooter>


    <sethtmlpageheader name="header" value="on" show-this-page="1" />
    <sethtmlpagefooter name="footer" value="on" show-this-page="1" />

    <div style="page-break-inside:avoid; background: url(<?= $url ?>images/export/pe-grid.jpg); overflow: hidden;" >
        <table align="center" style="page-break-inside:avoid;" >
            <tr>
                <td width="50"></td>
                <td>
                    <table width="100%">
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="33%">
                                            <table width="100%" >
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="90">
                                                        <table width="100%" >
                                                            <tr>
                                                                <td width="10" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding-top: 5px; text-transform: uppercase; color: #132555;">1</td>
                                                                <td align="center" valign="top" style="font-size: 50px; font-family: Arial; Font-weight: bold; margin: 0; padding: 0; line-height: 40px; color: #132555;">
                                                                    <?= ucfirst($blocks['target']->name[0]) ?>
                                                                </td>
                                                                <td width="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td align="center" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase;"><?= ucfirst($blocks['target']->name) ?></td>
                                                                <td width="20"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td  align="left" style="font-weight: bold; font-size: 18px; color: #555; font-family: Calibri;"><?= $blocks['target']->title ?></td>
                                                    <td width="40"></td>
                                                </tr>
                                               
                                            </table>
                                        </td>
                                        <td width="33%" >
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="90">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="10" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase; color: #132555;">2</td>
                                                                <td align="center" valign="top" style="font-size: 50px; font-family: Arial; Font-weight: bold; margin: 0; padding: 0; line-height: 40px; color: #132555;">
                                                                    <?= ucfirst($blocks['insight']->name[0]) ?>
                                                                </td>
                                                                <td width="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td align="center" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase;"><?= ucfirst($blocks['insight']->name) ?></td>
                                                                <td width="20"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="left" style="font-weight: bold; font-size: 18px; color: #555; font-family: Calibri;"><?= $blocks['insight']->title ?></td>
                                                    <td width="40"></td> 
                                                </tr>
                                              
                                            </table>
                                        </td>
                                        <td width="33%">
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="90">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="10" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase; color: #132555;">3</td>
                                                                <td align="center" valign="top" style="font-size: 50px; font-family: Arial; Font-weight: bold; margin: 0; padding: 0; line-height: 40px; color: #132555;">
                                                                    <?= ucfirst($blocks['alternatives']->name[0]) ?>
                                                                </td>
                                                                <td width="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td align="center" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase;"><?= ucfirst($blocks['alternatives']->name) ?></td>
                                                                <td width="20"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="left" style="font-weight: bold; font-size: 18px; color: #555; font-family: Calibri;"><?= ucfirst($blocks['alternatives']->title) ?></td>
                                                    <td width="40"></td>
                                                </tr>
                                               
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="33%" valign="top" height="400">
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="15"></td>
                                                    <td align="left" style="font-weight: bold; font-size: 15px; color: #000; font-family: Calibri;"><?= $blocks['target']->description ?></td>
                                                    <td width="10"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr >
                                                    <td></td>
                                                    <td align="left" style="font-size: 15px; color: #333; font-family: Calibri;"><?= isset($projectBlocks['target']) ? $projectBlocks['target']->content : null ?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="33%" valign="top">
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="10"></td>
                                                    <td align="left" style="font-weight: bold; font-size: 15px; color: #000; font-family: Calibri;"><?= $blocks['insight']->description ?></td>
                                                    <td width="15"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td align="left" style="font-size: 15px; color: #333; font-family: Calibri;"><?= isset($projectBlocks['insight']) ? $projectBlocks['insight']->content : null ?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="33%" valign="top">
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="10"></td>
                                                    <td align="left" style="font-weight: bold; font-size: 15px; color: #000; font-family: Calibri;"><?= $blocks['alternatives']->description ?></td>
                                                    <td width="20"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td align="left" style="font-size: 15px; color: #333; font-family: Calibri;"><?= isset($projectBlocks['alternatives']) ? $projectBlocks['alternatives']->content : null ?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" >
                                    <tr>
                                        <td width="33%">
                                            <table width="100%" >
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="90" >
                                                        <table width="100%" cellpadding="0" cellspacing="0" >
                                                            <tr>
                                                                <td width="10" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding:0px; text-transform: uppercase; color: #df1b7a;">4</td>
                                                                <td align="center" valign="top" style="font-size: 50px; font-family: Arial; Font-weight: bold; margin: 0; padding: 0; line-height: 40px; color: #df1b7a;">
                                                                    <?= ucfirst($blocks['benefits']->name[0]) ?>
                                                                </td>
                                                                <td width="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td align="center" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase;"><?= ucfirst($blocks['benefits']->name) ?></td>
                                                                <td width="20"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="left" style="font-weight: bold; font-size: 18px; color: #555; font-family: Calibri;"><?= $blocks['benefits']->title ?></td>
                                                    <td width="40"></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="33%">
                                            <table width="100%" cellpadding="0" cellspacing="0" >
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="90">
                                                        <table width="100%" cellpadding="0" cellspacing="0" >
                                                            <tr>
                                                                <td width="10" valign="top" style="font-size: 12px; padding-top:10px;font-family: Arial;text-transform: uppercase; color: #df1b7a;">5</td>
                                                                <td align="center" valign="top" style="font-size: 50px; font-family: Arial; Font-weight: bold; margin: 0; padding: 0; line-height: 40px; color: #df1b7a;">
                                                                    <?= ucfirst($blocks['reasons-to-believe']->name[0]) ?>
                                                                </td>
                                                                <td width="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td align="center" valign="top" style="font-size: 11px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase;"><?= ucfirst($blocks['reasons-to-believe']->name) ?></td>
                                                                <td width="20"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="left" style="font-weight: bold; font-size: 18px; color: #555; font-family: Calibri;"><?= $blocks['reasons-to-believe']->title ?></td>
                                                    <td width="40"></td> 
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="33%">
                                            <table width="100%" cellpadding="0" cellspacing="0">
                                                
                                                <tr>
                                                    <td width="30"></td>
                                                    <td width="90">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="10" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase; color: #df1b7a;">6</td>
                                                                <td align="center" valign="top" style="font-size: 50px; font-family: Arial; Font-weight: bold; margin: 0; padding: 0; line-height: 40px; color: #df1b7a;">
                                                                    <?= ucfirst($blocks['superiority']->name[0]) ?>
                                                                </td>
                                                                <td width="20"></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td align="center" valign="top" style="font-size: 12px; font-family: Arial; margin: 0; padding: 0; text-transform: uppercase;"><?= ucfirst($blocks['superiority']->name) ?></td>
                                                                <td width="20"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="left" style="font-weight: bold; font-size: 18px; color: #555; font-family: Calibri;"><?= $blocks['superiority']->title ?></td>
                                                    <td width="40"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" cellpadding="0" cellspacing="0"  >
                                    <tr>
                                        <td width="33%" valign="top" height="400">
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="15"></td>
                                                    <td align="left" style="font-weight: bold; font-size: 15px; color: #000; font-family: Calibri;"><?= $blocks['benefits']->description ?></td>
                                                    <td width="10"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td align="left" style="font-size: 15px; color: #333; font-family: Calibri;"><?= isset($projectBlocks['benefits']) ? $projectBlocks['benefits']->content : null ?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="33%" valign="top">
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="10"></td>
                                                    <td align="left" style="font-weight: bold; font-size: 15px; color: #000; font-family: Calibri;"><?= $blocks['reasons-to-believe']->description ?></td>
                                                    <td width="15"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td align="left" style="font-size: 15px; color: #333; font-family: Calibri;"><?= isset($projectBlocks['reasons-to-believe']) ? $projectBlocks['reasons-to-believe']->content : null ?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="33%" valign="top">
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td width="10"></td>
                                                    <td align="left" style="font-weight: bold; font-size: 15px; color: #000; font-family: Calibri;"><?= $blocks['superiority']->description ?></td>
                                                    <td width="20"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" height="10"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td align="left" style="font-size: 15px; color: #333; font-family: Calibri;"><?= isset($projectBlocks['superiority']) ? $projectBlocks['superiority']->content : null ?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="20" ></td>
            </tr>
        </table>
    </div>
</body>
</html>
