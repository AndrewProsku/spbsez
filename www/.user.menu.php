<?
$aMenuLinks = Array(
	Array(
		"Профиль", 
		"/cabinet/profile/", 
		Array(), 
		Array(), 
		"\$GLOBALS[\"USER\"]->IsAuthorized()" 
	),
	Array(
		"Подача отчета", 
		"/cabinet/report/", 
		Array(), 
		Array(),
        "\$GLOBALS[\"USER\"]->IsAuthorized()"
    ),
	Array(
		"Сообщение от ОЭЗ", 
		"/cabinet/messages/", 
		Array(), 
		Array(),
        "\$GLOBALS[\"USER\"]->IsAuthorized()"
    ),
	Array(
		"Подать заявку", 
		"/cabinet/request/", 
		Array(), 
		Array(),
        "\$GLOBALS[\"USER\"]->IsAuthorized()"
    )
);
?>