<?php

// Rota de Login (GET)

use App\Http\Response;
use App\Controller\Admin;

$obRouter->get('/admin/login', [
  'middlewares' => ['required-admin-logout'],
  function ($request) {
    return new Response(200, Admin\login::getLogin($request));
  }
]);

// Rota de Login (POST)
$obRouter->post('/admin/login', [
  'middlewares' => ['required-admin-logout'],
  function ($request) {
    return new Response(200, Admin\Login::setLogin($request));
  }
]);

// Rota de Logout (GET)
$obRouter->get('/admin/logout', [
  'middlewares' => ['required-admin-login'],
  function ($request) {
    return new Response(200, Admin\Login::setLogout($request));
  }
]);
