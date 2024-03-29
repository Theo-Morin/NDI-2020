<?php
session_start();
date_default_timezone_set('Europe/Paris');

require_once './components/models/_db.php';
require_once './components/models/Other.php';
require_once './components/models/MeteoAPI.php';
require_once './components/models/User.php';
require_once './components/models/SurfData.php';

define('SERVER_URL', "http://51.210.38.134/");

$title = "Surfrider | ";
$view = 'error_docs/404.php';

require_once './components/controllers/home_controller.php';

$isLogged = User::isLogged();

$stylesheet = [];
$stylesheet[] = "/public/vendors/bootstrap/bootstrap.css";
$stylesheet[] = "/public/assets/css/style.css";
$scripts = [];
$scripts[] = "/public/vendors/jquery.js";
$scripts[] = "/public/vendors/bootstrap/bootstrap.js";
$scripts[] = "/public/assets/js/site.js";

include('./components/views/partials/head.php');
include('./components/views/partials/nav.php');
include('./components/views/' . $view);
include('./components/views/partials/footer.php');

?>
