<?php

use config\Config;
use services\SessionService;
use services\UserService;

require_once 'services/SessionService.php';
require_once 'services/UserService.php';
require_once 'config/Config.php';
require_once 'models/User.php';

$sessionService = SessionService::getInstance();
$userService = UserService::getInstance(Config::getInstance()->db);

if ($sessionService->loggedIn) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    $errors = [];
    if (empty($username)) {
        $errors[] = 'El nombre de usuario es requerido';
    }
    if (empty($password)) {
        $errors[] = 'La contraseña es requerida';
    }
    if (empty($confirmPassword)) {
        $errors[] = 'La confirmación de la contraseña es requerida';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Las contraseñas no coinciden';
    }

    if (empty($errors)) {
        try {
            $userService->register($username, $password);
            $user = $userService->authenticate($username, $password);
            $sessionService->login($user);
            header('Location: /index.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'El usuario ya existe';
        }
    }
}

include 'tailwind.php';
?>

<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:min-h-screen lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Regístrate
                </h1>
                <form class="space-y-4 md:space-y-6" method="post">
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre
                            de usuario</label>
                        <input name="username" id="username"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required="">
                    </div>
                    <div>
                        <label for="confirm-password"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar
                            contraseña</label>
                        <input name="confirm-password" id="confirm-password"
                               type="password"
                               placeholder="••••••••"
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               required="">
                    </div>
                    <div>
                        <p class="text-sm text-red-500 dark:text-red-400"><?php echo implode('<br>', $errors ?? []); ?></p>
                    </div>
                    <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Registrarse
                    </button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        ¿Ya tienes una cuenta? <a href="/login.php"
                                                  class="font-medium text-blue-600 hover:underline dark:text-blue-500">Inicia
                            sesión aquí</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
