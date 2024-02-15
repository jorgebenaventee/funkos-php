<?php

use services\SessionService;

require_once 'services/SessionService.php';
require_once 'services/UserService.php';
require_once 'config/Config.php';
require_once 'models/User.php';

$sessionService = SessionService::getInstance();

include 'tailwind.php';
include 'admin-check.php';
include 'header.php';
?>

<main class="min-h-screen bg-gray-900 text-white">
    <h1>Categorías :)</h1>
</main>

