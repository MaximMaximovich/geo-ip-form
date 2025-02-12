<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
	"NAME" => Loc::getMessage("GEOIP_INFO_COMPONENT_NAME"),
	"DESCRIPTION" => Loc::getMessage("GEOIP_INFO_COMPONENT_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "impulsit",
        "NAME" => Loc::getMessage("GEOIP_INFO_COMPONENT_PATH_NAME"),
	),
);
?>