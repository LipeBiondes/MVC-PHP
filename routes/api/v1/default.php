<?php

use App\Controller\Api;
use \App\Http\Response;

// Rota raiz da API
$obRouter->get('/api/v1', [
  'middlewares' => ['api'],
  function ($request) {
    return new Response(200, Api\Api::getDetails($request), 'application/json');
  }
]);
