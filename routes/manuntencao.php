<?php

use App\Http\Response;
use App\Controller\Maintenance\Page;

// Rota de Manuntenção
$obRouter->get('/manutencao', [
  function () {
    return new Response(200, Page::getPage());
  }
]);
