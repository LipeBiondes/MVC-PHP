<?php

namespace App\Http\Middleware;

use App\Utils\View;

class Maintenance
{

  /**
   * Método responsável por executar o middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle($request, $next)
  {
    // Verifica se o site está em manutenção
    if (getenv('MAINTENANCE') == 'true') {
      // Exibe a página de manuntenção
      echo View::render('maintenance/manutencao');
      exit;
    }

    // Executa o próximo nível do middleware
    return $next($request);
  }
}
