<?php

use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'services/SessionService.php';
require_once 'services/FunkoService.php';
require_once 'services/SessionService.php';
require_once 'models/Funko.php';
require_once 'config/Config.php';
$funkoService = FunkoService::getInstance(Config::getInstance()->db);
$funkos = $funkoService->getFunkos();
$sessionService = SessionService::getInstance();
$isAdmin = $sessionService->isAdmin ?? false;
include "tailwind.php";
include "header.php";
?>

    <main class="min-h-screen grid grid-cols-4 p-4 bg-gray-900 gap-4 items-center justify-center">
        <?php foreach ($funkos as $funko): ?>
            <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 m-auto">
                <a href="#" class="p-2">
                    <img class="rounded block m-auto" src="<?= $funko->getImageUrl() ?>" alt=""/>
                </a>
                <div class="p-5 min-w-[400px]">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        <?= $funko->name ?>
                    </h5>
                    <small class="opacity-50 text-white"><?= $funko->category_name ?></small>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        <?= $funko->price ?>€
                    </p>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        <?= $funko->stock ?> unidades en stock
                    </p>
                    <div class="flex gap-1 flex-wrap">
                        <a href="/funko-details.php?id=<?= $funko->id ?>"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Ver detalles
                        </a>
                        <?php if ($isAdmin): ?>
                            <a href="/update-funko.php?id=<?= $funko->id ?>"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-indigo-700 rounded-lg hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                                Actualizar
                            </a>
                            <a href="/update-funko-image.php?id=<?= $funko->id ?>"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-violet-700 rounded-lg hover:bg-violet-800 focus:ring-4 focus:outline-none focus:ring-violet-300 dark:bg-violet-600 dark:hover:bg-violet-700 dark:focus:ring-violet-800">
                                Actualizar imagen
                            </a>
                            <a href="#"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Borrar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
<?php include "footer.php"; ?>