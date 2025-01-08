<?php

require __DIR__ . '/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

// Carrega as variáveis de ambiente
Environment::load(__DIR__ . '/../');

// Define os dados de conexão com o banco de dados
Database::config(
  getenv('DB_HOST'),
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS'),
  getenv('DB_PORT')
);

// Define a URL do projeto
define('URL', getenv('URL'));

// Define o valor padrão das variáveis
View::init([
  'URL' => URL
]);

// Define o mapeamento de middlewares
MiddlewareQueue::setMap([
  'maintenance' => \App\Http\Middleware\Maintenance::class,
  'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
  'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class
]);

// Define os middlewares padrões a serem carregados em todas as rotas
MiddlewareQueue::setDefault([
  'maintenance'
]);
