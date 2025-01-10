<?php

// Inclui o arquivo de inicialização
require __DIR__ . '/includes/app.php';

use \App\Http\Router;

// Inicia o Router
$obRouter = new Router(URL);

// Inclui as rotas de páginas
include __DIR__ . '/routes/pages.php';

// Inclui as rotas de admin
include __DIR__ . '/routes/admin.php';

// Inclui as rotas de manutenção
include __DIR__ . '/routes/manuntencao.php';

// Inclui as rotas da API
include __DIR__ . '/routes/api.php';

// Imprime o response da rota
$obRouter->run()->sendResponse();
