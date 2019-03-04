<?
$aMenuLinks = Array(
    Array(
        "Личные данные",
        "/en/cabinet/",
        Array(),
        Array(),
        "\$GLOBALS[\"USER\"]->IsAuthorized()"
    ),
	Array(
		"Документы",
		"/en/cabinet/docs/",
		Array(), 
		Array(), 
		"\$GLOBALS[\"USER\"]->IsAuthorized()"
	),
	Array(
		"Администраторы",
		"/en/cabinet/admins/",
		Array(),
		Array("check"=>"canEditResident"),
		"false"
	),
);
?>
