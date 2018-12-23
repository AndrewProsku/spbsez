<?
$aMenuLinks = Array(
    Array(
        "Личные данные",
        "/cabinet/",
        Array(),
        Array(),
        "\$GLOBALS[\"USER\"]->IsAuthorized()"
    ),
	Array(
		"Документы",
		"/cabinet/docs/",
		Array(), 
		Array(), 
		"\$GLOBALS[\"USER\"]->IsAuthorized()"
	),
	Array(
		"Администраторы",
		"#",
		Array(),
		Array(),
		"\$GLOBALS[\"USER\"]->IsAuthorized()"
	),
);
?>
