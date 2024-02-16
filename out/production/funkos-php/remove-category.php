<?php

use config\Config;
use services\CategoryService;
use services\SessionService;

require_once 'services/SessionService.php';
require_once 'services/UserService.php';
require_once 'services/CategoryService.php';
require_once 'config/Config.php';
require_once 'models/User.php';

$sessionService = SessionService::getInstance();
$categoryService = CategoryService::getInstance(Config::getInstance()->db);


include 'admin-check.php';

$message = 'La categoría se ha borrado correctamente';

$id = $_GET['id'];
$category = $categoryService->getCategoryById($id);
if (!$category) {
    $message = 'La categoría no existe';
} else if ($categoryService->hasFunkos($id)) {
    $message = 'La categoría no se puede borrar porque tiene funkos asociados';
} else {
    $categoryService->deleteCategory($id);
}


echo "<script>alert('$message'); window.location.href = '/categories.php'</script>";
exit;
