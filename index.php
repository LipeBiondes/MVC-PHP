<?php

// Inclui o arquivo de inicialização
require __DIR__ . '/includes/app.php';

use \App\Http\Router;

// Inicia o Router
$obRouter = new Router(URL);

// Inclui as rotas de páginas
include __DIR__ . '/routes/pages.php';
include __DIR__ . '/routes/admin.php';
include __DIR__ . '/routes/manuntencao.php';

// Imprime o response da rota
$obRouter->run()->sendResponse();
