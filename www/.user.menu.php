<?
$aMenuLinks = Array(
	Array(
		"Профиль", 
		"/cabinet/",
		Array(),
		Array("check"=>"hasAccess"),
		"\$GLOBALS[\"USER\"]->IsAuthorized()" 
	),
	Array(
		"Подача отчета", 
		"/cabinet/report/", 
		Array(),
        Array("check"=>"canReport"),
        "false"
    ),
	Array(
		"Сообщение от ОЭЗ", 
		"/cabinet/messages/", 
		Array(),
        Array("check"=>"canMessages"),
        "false"
    ),
	Array(
		"Подать заявку", 
		"/cabinet/request/", 
		Array(),
        Array("check"=>"canRequest"),
        "false"
    )
);
?>
