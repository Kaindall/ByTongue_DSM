<?php
require_once 'src\main\domain\model\request\RequestHandler.php';
require_once 'src\main\application\router\Router.php';

use src\main\domain\model\request\RequestHandler;


$request = new RequestHandler();
$response = Router::getInstance()->redirect($request);
echo "<br>$response";
return;