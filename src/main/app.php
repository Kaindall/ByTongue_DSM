<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\router\Router.php';

use src\main\domain\model\request\RequestHandler;

ob_start();
$request = new RequestHandler();
$response = Router::getInstance()->redirect($request);
echo "<br>$response";
ob_end_flush();
return;