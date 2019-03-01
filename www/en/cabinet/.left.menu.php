<?
$aMenuLinks = Array(
	Array(
		"Вход в личный кабинет", 
		"/en/cabinet/auth/",
		Array(), 
		Array(), 
		"!\$GLOBALS[\"USER\"]->IsAuthorized()" 
	),
	Array(
		"Восстановление пароля", 
		"/en/cabinet/forgot/",
		Array(), 
		Array(), 
		"!\$GLOBALS[\"USER\"]->IsAuthorized()" 
	),
	Array(
		"Профиль", 
		"/en/cabinet/",
		Array(),
		Array("check"=>"hasAccess"),
		"\$GLOBALS[\"USER\"]->IsAuthorized()"
	),
	Array(
		"Подача отчета",
		"/en/cabinet/report/",
		Array(),
		Array("check"=>"canReport"),
		"false"
	),
	Array(
		"Сообщения от ОЭЗ",
		"/en/cabinet/messages/",
		Array(),
		Array("check"=>"canMessages", "isMessages"=>"1"),
		"false"
	),
	Array(
		"Подать заявку",
		"/en/cabinet/request/",
		Array(),
		Array("check"=>"canRequest"),
		"false"
	)
);
?>
