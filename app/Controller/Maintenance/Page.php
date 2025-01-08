<?php

namespace App\Controller\Maintenance;

use App\Utils\View;

class Page
{
  /**
   * Método responsável por retornar o conteúdo (view) da manuntenção
   * @return string
   */
  public static function getPage()
  {
    // Retorna a view da página
    return View::render('maintenance/manutencao');
  }
}
