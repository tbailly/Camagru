<?php

define("ROOT_D"			, realpath($_SERVER['DOCUMENT_ROOT'] . '/..'));

define("MODELS_D"		, ROOT_D . "/models");
define("CLASSES_D"		, ROOT_D . "/classes");
define("VIEWS_D"		, ROOT_D . "/views");
define("CONFIG_D"		, ROOT_D . "/config");

define("PUBLIC_D"		, ROOT_D . '/public');
define("CONTROLLERS_D"	, PUBLIC_D . "/controllers");
define("PICTURES_D"		, PUBLIC_D ."/pictures");
define("FILTERS_D"		, PUBLIC_D . "/filters");
define("JS_D"			, "/js");
define("CSS_D"			, "/css");
define("VENDOR_D"		, "/vendor");

include_once CONFIG_D . '/database.php';
