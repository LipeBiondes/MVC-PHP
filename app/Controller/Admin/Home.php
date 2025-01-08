<?php

namespace App\Controller\Admin;

use App\Model\Entity\User;
use App\Utils\View;

class Home extends Page
{
  /**
   * Método responsável por retornar o conteúdo (view) da home do painel
   * @param Request $request
   * @return string
   */
  public static function getHome($request)
  {
    // Conteúdo da home
    $content = View::render('admin/modules/home/index', []);

    // Retorna a página completa
    return parent::getPanel('Home > Wdev', $content, 'home');
  }
}
