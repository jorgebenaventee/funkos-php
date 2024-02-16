<?php
global $sessionService;

if (!$sessionService->isAdmin) {
    echo '<script>alert("No tienes permiso para ir a esta sección"); window.location.href = "/index.php"</script>';
    exit;
}
