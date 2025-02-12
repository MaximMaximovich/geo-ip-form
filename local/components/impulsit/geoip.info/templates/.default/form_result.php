<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var CBitrixComponent $component */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
?>

<?php if(!empty($arResult['DATA'])) :?>
<label for="formService" class="form-label"><?= Loc::getMessage("GEOIP_INFO_FORM_RESULT_TITLE") ?></label>
<table class="table">
    <tbody>
    <?php foreach($arResult['DATA'] as $key => $value) :?>
        <tr>
            <th><?=$arResult['LABELS'][$key]?></th>
            <td><?=$value?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

