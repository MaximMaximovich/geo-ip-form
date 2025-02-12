<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
	"PARAMETERS" => array(
		"HLBLOCK_ID" => array(
		    "PARENT" => "BASE",
			"NAME" => Loc::getMessage("GEOIP_INFO_HLBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "IPSTACK_ACCESS_KEY" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("GEOIP_INFO_IPSTACK_ACCESS_KEY"),
            "TYPE" => "STRING",
        ),
        "MAIL_MESSAGE_ID" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("GEOIP_INFO_MAIL_MESSAGE_ID"),
            "TYPE" => "STRING",
        ),
        "MAIL_EVENT_NAME" => array(
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("GEOIP_INFO_MAIL_EVENT_NAME"),
            "TYPE" => "STRING",
        ),
	),
);




