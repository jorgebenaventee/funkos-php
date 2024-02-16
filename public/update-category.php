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

$errors = ['name' => false];

if (!isset($_GET['id'])) {
    header('Location: /categories.php');
}

$id = $_GET['id'] ?? $_POST['id'];
$category = $categoryService->getCategoryById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    if (empty($name)) {
        $errors['name'] = 'required';
    } else if ($categoryService->exists($name)) {
        $errors['name'] = 'exists';
    }
    if (!$errors['name']) {
        $categoryService->updateCategory($id, $name);
        header('Location: /categories.php');
    }
}


include 'admin-check.php';
include 'tailwind.php';
include 'header.php';
?>

    <div class="min-h-screen flex items-center bg-gray-900">
        <form class="max-w-sm mx-auto" method="POST">
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                <input type="text" id="name" name="name"
                       value="<?= $category->name ?>"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Deportes" required/>
                <?php if ($errors['name'] === 'required'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">El nombre es requerido</p>
                <?php elseif ($errors['name'] === 'exists'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">La categoría ya existe</p>
                <?php endif; ?>
                <input type="hidden" value="<?= $id ?>" name="id">
            </div>
            <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Actualizar categoría
            </button>
        </form>

    </div>
<?php
include 'footer.php';
?>