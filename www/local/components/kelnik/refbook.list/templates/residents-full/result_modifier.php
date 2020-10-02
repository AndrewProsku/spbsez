<?php
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$residentId = $request->get('resident');
$arResult["DETAIL_DATA"] = [];
if($residentId){
	$arResult["DETAIL_DATA"] = $arResult["ELEMENTS"][$residentId];
}