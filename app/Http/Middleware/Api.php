<?php

namespace App\Http\Middleware;

use App\Utils\View;

class Api
{

  /**
   * Método responsável por executar o middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle($request, $next)
  {
    // Altera o content type para JSON
    $request->getRouter()->setContentType('application/json');

    // Executa o próximo nível do middleware
    return $next($request);
  }
}
