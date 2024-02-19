<?php

require_once __DIR__ . '/services/SessionService.php';

use services\SessionService;

$sessionService = SessionService::getInstance();

$sessionService->logout();