<?php

use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'services/FunkoService.php';
require_once 'services/SessionService.php';
require_once 'models/Funko.php';
require_once 'config/Config.php';
$funkoService = FunkoService::getInstance(Config::getInstance()->db);
$sessionService = SessionService::getInstance();
$id = $_GET['id'];
if (!isset($id) || !$funkoService->existsById($id)) {
    echo "<script>alert('No se ha encontrado el funko'); window.location.href = '/index.php'</script>";
}

$funko = $funkoService->getFunkoById($id);

include 'tailwind.php';
include 'header.php';
?>
<div class="grid grid-cols-2 min-h-screen items-center justify-center bg-gray-900">
    <div class="flex flex-col items-center">
        <dl class="flex flex-col gap-3">
            <div class="flex flex-col">
                <dt class="font-semibold">Nombre</dt>
                <dd><?= $funko->name ?></dd>
            </div>

            <div class="flex flex-col">
                <dt class="font-semibold">Categoría</dt>
                <dd><?= $funko->category_name ?></dd>
            </div>

            <div class="flex flex-col">
                <dt class="font-semibold">Precio</dt>
                <dd><?= $funko->price ?>€</dd>
            </div>

            <div class="flex flex-col">
                <dt class="font-semibold">Stock</dt>
                <dd><?= $funko->stock ?> unidades</dd>
            </div>
        </dl>

        <a href="/index.php"
           class="text-white my-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Volver</a>
    </div>
    <div class="">
        <img src="<?= $funko->getImageUrl() ?>" class="w-full max-w-96 rounded"
             alt="Foto del funko <?= $funko->name ?>">
    </div>
</div>
<?php include 'footer.php' ?>
