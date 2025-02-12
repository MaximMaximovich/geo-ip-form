<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Форма GeoIP"); ?>


<?php $APPLICATION->IncludeComponent(
	"impulsit:geoip.info", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"HLBLOCK_ID" => GEO_HL_ID,
		"IPSTACK_ACCESS_KEY" => IPSTACK_KEY,
		"MAIL_MESSAGE_ID" => ERROR_MAIL_MESSAGE_ID,
		"MAIL_EVENT_NAME" => ERROR_MAIL_EVENT_NAME
	),
	false
);?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>