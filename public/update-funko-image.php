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
$errors = ['file' => false];
$id = $_GET['id'] ?? $_POST['id'];

$funko = $funkoService->getFunkoById($id);

if (!$funko) {
    echo "<script>alert('No se ha encontrado el funko'); window.location.href = '/index.php'</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'];
    if (!isset($file) || empty($file['name'])) {
        $errors['file'] = 'required';
    } else if (!in_array($file['type'], ['image/jpeg', 'image/png'])) {
        $errors['file'] = 'invalid';
    }

    if (!$errors['file']) {
        $funkoService->updateImage($funko, $file);
        header('Location: /index.php');
    }
}
include 'tailwind.php';
include "header.php";
?>

    <div class="min-h-screen flex items-center bg-gray-900">
        <form class="max-w-sm mx-auto" method="POST" enctype="multipart/form-data">
            <div class="mb-5">
                <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Elige un
                    archivo</label>
                <input type="file" id="file" name="file"
                       accept="image/*"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      required/>
                <?php if ($errors['file'] === 'invalid'): ?>
                    <p class="text-sm text-red-500 dark:text-red-400">Seleccione un archivo de imagen</p>
                <?php endif; ?>
                <input type="hidden" value="<?= $id ?>" name="id">
            </div>
            <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Actualizar imagen
            </button>
        </form>

    </div>

<?php include 'footer.php' ?>