<?php

use config\Config;
use services\CategoryService;
use services\FunkoService;
use services\SessionService;

require_once 'services/SessionService.php';
require_once 'services/UserService.php';
require_once 'services/CategoryService.php';
require_once 'services/FunkoService.php';
require_once 'config/Config.php';
require_once 'models/User.php';

$sessionService = SessionService::getInstance();
$categoryService = CategoryService::getInstance(Config::getInstance()->db);
$funkoService = FunkoService::getInstance(Config::getInstance()->db);
$errors = ['name' => false, 'price' => false, 'stock' => false, 'category' => false];
$id = $_GET['id'] ?? $_POST['id'];

include 'admin-check.php';
$funko = $funkoService->getFunkoById($id);
if (!$funko) {
    echo "<script>alert('No se ha encontrado el funko'); window.location.href = '/index.php'</script>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    if (empty($name)) {
        $errors['name'] = 'required';
    }
    if (empty($price)) {
        $errors['price'] = 'required';
    } else if (!is_numeric($price)) {
        $errors['price'] = 'invalid';
    }

    if (empty($stock)) {
        $errors['stock'] = 'required';
    } else if (!is_numeric($stock)) {
        $errors['stock'] = 'invalid';
    }

    if (empty($category)) {
        $errors['category'] = 'required';
    } else if (!$categoryService->getCategoryById($category)) {
        $errors['category'] = 'invalid';
    }

    if (!$errors['name']) {
        $funko->name = $name;
        $funko->price = $price;
        $funko->stock = $stock;
        $funko->category_id = $category;
        $funkoService->update($funko);
        header('Location: /index.php');
    }

}


include 'tailwind.php';
include 'header.php';
?>

    <div class="min-h-screen flex items-center bg-gray-900">
        <form class="max-w-sm mx-auto" method="POST">
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                <input type="text" id="name" name="name"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       value="<?= $funko->name ?>"
                       placeholder="John Cena" required/>
                <?php if ($errors['name'] === 'required'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">El nombre es requerido</p>
                <?php elseif ($errors['name'] === 'exists'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">La categoría ya existe</p>
                <?php endif; ?>
            </div>
            <div class="mb-5">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio</label>
                <input type="number" id="price" name="price"
                       value="<?= $funko->price ?>"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="10" required/>
                <?php if ($errors['price'] === 'required'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">El precio es requerido</p>
                <?php elseif ($errors['price'] === 'invalid'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">El precio es inválido</p>
                <?php endif; ?>
            </div>
            <div class="mb-5">
                <label for="stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock</label>
                <input type="number" id="stock" name="stock"
                       value="<?= $funko->stock ?>"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="10" required/>
                <?php if ($errors['stock'] === 'required'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">El stock es requerido</p>
                <?php elseif ($errors['stock'] === 'invalid'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">El stock es inválido</p>
                <?php endif; ?>
            </div>
            <div class="mb-5">
                <label for="category"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría</label>
                <select id="category" name="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <?php foreach ($categoryService->getCategories() as $category): ?>
                        <option value="<?= $category->id ?>" <?= $funko->category_id === $category->id ? 'selected' : '' ?>><?= $category->name ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($errors['category'] === 'required'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">La categoría es requerida</p>
                <?php elseif ($errors['category'] === 'invalid'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">La categoría es inválida</p>
                <?php endif; ?>
            </div>
            <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
               Actualizar funko
            </button>
        </form>

    </div>
<?php
include 'footer.php';
?>