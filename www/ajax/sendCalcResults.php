<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if($request->isPost()){
	if($request->get("email")){		
		$event = "CALC_RESULT_PERSONAL";
	}else{
		$event = "CALC_RESULT";
	}

	Bitrix\Main\Mail\Event::send(array(
	    "EVENT_NAME" => $event,
	    "LID" => "s1",
	    "C_FIELDS" => array(
	        "OFFICES" => $request->get("offices") ? $request->get("offices") : 0,
	        "PRODUCTION" => $request->get("production") ? $request->get("production") : 0,
	        "LAND" => $request->get("land") ? $request->get("land") : 0,
	        "LIGHT" => $request->get("light") ? $request->get("light") : 0,
	        "ADMINISTRATIVE" => $request->get("administrative") ? $request->get("administrative") : 0,
	        "SCIENCE" => $request->get("science") ? $request->get("science") : 0,
	        "FULL_AREA" => $request->get("fullArea") ? $request->get("fullArea") : 0,
	        "OFFICE_COST_RENT" => $request->get("office_cost_rent") ? $request->get("office_cost_rent") : 0,
	        "PRODUCTION_COST_RENT" => $request->get("production_cost_rent") ? $request->get("production_cost_rent") : 0,
	        "LAND_COST_RENT" => $request->get("land_cost_rent") ? $request->get("land_cost_rent") : 0,
	        "LAND_COST_BUY" => $request->get("land_cost_buy") ? $request->get("land_cost_buy") : 0,
	        "MIN_INVEST" => $request->get("min_invest") ? $request->get("min_invest") : 0,
	        "EMAIL_PERSONAL" => $request->get("email") ? $request->get("email") : ""
	    ),
	));
}