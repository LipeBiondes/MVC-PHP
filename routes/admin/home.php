<?php

use App\Http\Response;
use App\Controller\Admin;

// Rota Admin
$obRouter->get('/admin', [
  'middlewares' => ['required-admin-login'],
  function ($resquest) {
    return new Response(200, Admin\Home::getHome($resquest));
  }
]);
