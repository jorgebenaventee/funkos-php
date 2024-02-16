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


$categories = $categoryService->getCategories(true);

include 'tailwind.php';
include 'admin-check.php';
include 'header.php';
?>

<main class="min-h-screen bg-gray-900 text-white">
    <h1 class="text-2xl text-center font-bold">Categorías</h1>
    <div class="relative overflow-x-auto rounded mt-7">
        <table class="w-full max-w-2xl m-auto rounded text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption>
                <a href="/new-category.php" class="font-medium text-blue-500 hover:underline text-right block p-4">Crear
                    categoría</a>

            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="text-center px-6 py-3">
                    Nombre
                </th>
                <th scope="col" class="text-center px-6 py-3">
                    Activada
                </th>
                <th scope="col" class="text-center px-6 py-3">
                    <span>Acciones</span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                        <?= $category->name ?>
                    </th>
                    <td class="px-6 py-4 text-center">
                        <?= $category->is_deleted ? 'No' : 'Sí' ?>
                    </td>
                    <td class="px-6 py-4 text-center flex gap-3 justify-center">
                        <a href="/update-category.php?id=<?= $category->id ?>"
                           class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                        <?php if ($category->is_deleted): ?>
                            <a href="/restore-category.php?id=<?= $category->id ?>"
                               class="font-medium text-green-600  hover:underline">Activar</a>
                        <?php else: ?>
                            <a href="/soft-delete-category.php?id=<?= $category->id ?>"
                               class="font-medium text-gray-600  hover:underline">Desactivar</a>
                        <?php endif; ?>
                        <?php if (!$categoryService->hasFunkos($category->id)): ?>
                            <a href="/remove-category.php?id=<?= $category->id ?>"
                               class="font-medium text-red-600  hover:underline">Borrar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

