<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
global $APPLICATION;
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    CJSCore::Init(array("ajax"));
    Bitrix\Main\UI\Extension::load("ui.vue");
    ?>
    <title><?php $APPLICATION->ShowTitle(false); ?></title>
    <?php
    $APPLICATION->ShowHead();
    Asset::getInstance()->addString('<meta charset="UTF-8"/>');
    Asset::getInstance()->addString('<meta http-equiv="X-UA-Compatible" content="IE=edge"/>');
    Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/styles.css');
    Asset::getInstance()->addCss('http://fonts.googleapis.com/css?family=Oswald:400,300');

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/libs/jquery3.6.1.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/libs/parsley.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/libs/jquery.mask.min.js');
    ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php $APPLICATION->ShowPanel();?>
<div class="wrapper container">
    <header></header>
    <?php $APPLICATION->IncludeComponent("bitrix:menu", "top", Array(
        "ROOT_MENU_TYPE" => "top",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => "",
        "MAX_LEVEL" => "1",
        "CHILD_MENU_TYPE" => "left",
        "USE_EXT" => "N",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
    ),
        false
    );?>
    <div class="heading">
        <h1><?php $APPLICATION->ShowTitle()?></h1>
    </div>
    <div class="row">
        <section class="col-md-24">