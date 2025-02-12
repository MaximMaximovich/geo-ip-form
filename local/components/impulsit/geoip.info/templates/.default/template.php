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

<div class="row">
    <div class="col-md-12">
        <form id="geoForm" method="post" action="<?=POST_FORM_ACTION_URI?>"
            data-ar-params="<?=$this->__component->getSignedParameters()?>"
            data-addi-signed="<?=$arResult["additionalSignedParams"]?>">
            <input type="hidden" name="mode" value="<?= $arResult['HIDDEN'] ?>"/>
            <div class="form-group mb-3">
                <label for="formIp" class="form-label"><?= Loc::getMessage("GEOIP_INFO_IP_ADDRESS_TXT") ?></label>
                <input
                        type="text"
                        name="ip_address"
                        id="formIp"
                        maxlength="15"
                        size="15"
                        class="form-control"
                        placeholder="xxx.xxx.xxx.xxx"
                        data-parsley-trigger="input "
                        required
                        data-parsley-required-message="Введите IP адрес"
                        data-parsley-error-message="Неверный формат IP адреса"
                        data-parsley-pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}"
                        data-parsley-errors-container="#formIpError"/>
                <small class="text-muted"><?= Loc::getMessage("GEOIP_INFO_IP_ADDRESS_HINT") ?></small>
                <small id="formIpError" class="text-muted" style="color:red"></small>
            </div>
            <div class="form-group mb-3">
                <label for="formService" class="form-label"><?= Loc::getMessage("GEOIP_INFO_SERVICE_TXT") ?></label>
                <select class="form-control" id="formService" name="geo_service">
                    <option><?= Loc::getMessage("GEOIP_INFO_SYPEGEO") ?></option>
                    <?php if (!empty($arParams['IPSTACK_ACCESS_KEY'])) : ?>
                    <option><?= Loc::getMessage("GEOIP_INFO_IPSTACK") ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?= Loc::getMessage("GEOIP_INFO_SEARCH_BTN_TXT") ?></button>
        </form>
    </div>
    <div id="formRes" class="col-md-12"></div>
</div>


