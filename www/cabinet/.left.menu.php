<?
$aMenuLinks = Array(
	Array(
		"Вход в личный кабинет", 
		"/cabinet/auth/", 
		Array(), 
		Array(), 
		"!\$GLOBALS[\"USER\"]->IsAuthorized()" 
	),
	Array(
		"Восстановление пароля", 
		"/cabinet/forgot/", 
		Array(), 
		Array(), 
		"!\$GLOBALS[\"USER\"]->IsAuthorized()" 
	),
	Array(
		"Профиль", 
		"/cabinet/", 
		Array(), 
		Array(), 
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
		"Сообщения от ОЭЗ", 
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
