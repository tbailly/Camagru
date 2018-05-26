<?php

define("ROOT_D", "/Camagru");
define("ABSPATH_D", $_SERVER['DOCUMENT_ROOT'] . '/camagru');

define("MODELS_D", ABSPATH_D . "/models");
define("CLASSES_D", ABSPATH_D . "/classes");
define("VIEWS_D", ABSPATH_D . "/views");
define("PICTURES_D", ABSPATH_D . "/pictures");
define("CONFIG_D", ABSPATH_D . "/config");

define("PUBLIC_D", ROOT_D . "/public");
define("VENDOR_D", ROOT_D . "/vendor");
define("JS_D", PUBLIC_D . "/js");
define("CSS_D", PUBLIC_D . "/css");

include_once CONFIG_D . '/database.php';
