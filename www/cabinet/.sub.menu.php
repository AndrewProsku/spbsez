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
		"/cabinet/admins/",
		Array(),
		Array("check"=>"canEditResidentAdmin"),
		"false"
	),
);
?>
