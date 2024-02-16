<?php

global $sessionService;
include "tailwind.php";
?>
<footer class="bg-white shadow flex items-center justify-center gap-2  p-4 sm:p-6 xl:p-8 dark:bg-gray-800 antialiased">
    <p class="mb-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:mb-0">
        &copy; Jorge Benavente.
    </p>
    <div class="flex justify-center items-center space-x-1 text-sm text-center text-gray-500 dark:text-gray-400 sm:mb-0">
        <?= $sessionService->visits ?> visitas desde tu último inicio de sesión
    </div>
</footer>
