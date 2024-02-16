<?php

use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'services/SessionService.php';
require_once 'services/UserService.php';
require_once 'services/FunkoService.php';
require_once 'config/Config.php';
require_once 'models/User.php';

$sessionService = SessionService::getInstance();
$funkoService = FunkoService::getInstance(Config::getInstance()->db);


include 'admin-check.php';

$message = 'El funko se ha borrado correctamente';

$id = $_GET['id'];
$category = $funkoService->getFunkoById($id);
if (!$category) {
    $message = 'El funko no existe';
} else {
    $funkoService->remove($id);
}


echo "<script>alert('$message'); window.location.href = '/index.php'</script>";
exit;
