<?php

if(!empty($arResult["QUESTIONS"]) && $arParams["USE_TYPES"] == "Y"){
	$arResult["QUESTIONS_BY_TYPES"] = [];
	$arResult["QUESTIONS_TYPES"] = [];
	foreach($arResult["QUESTIONS"] as $question){
		$arResult["QUESTIONS_BY_TYPES"][$question["TYPE_ID"]][] = $question;
		if($question["LANG"] == "ru"){
			$arResult["QUESTIONS_TYPES"][$question["TYPE_ID"]]["NAME"] = $question["TYPE_NAME"];
		}else{
			$arResult["QUESTIONS_TYPES"][$question["TYPE_ID"]]["NAME"] = $question["TYPE_NAME_EN"];
		}
	}
}