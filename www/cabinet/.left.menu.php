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
);
?>