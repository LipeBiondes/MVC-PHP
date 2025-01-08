<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Page
{
  /**
   * Módulos disponíveis no painel e suas respectivas views
   * @var array
   */
  private static $modules = [
    'home' => [
      'label' => 'Home',
      'link' => URL . '/admin',
    ],
    'testimonies' => [
      'label' => 'Depoimentos',
      'link' => URL . '/testimonies',
    ],
    'users' => [
      'label' => 'Usuários',
      'link' => URL . '/user',
    ],
  ];

  /**
   * Método responsável por retornar o conteúdo (view) da estrutura genérica de pagina do painel
   * @param string $title
   * @param string $content
   * @return string
   */
  public static function getPage($title, $content)
  {
    // Retorna a view da página
    return View::render('admin/page', [
      'title' => $title,
      'content' => $content
    ]);
  }

  /**
   * Método responsável por renderizar o menu do painel
   * @param string $currentModule
   * @return string
   */
  private static function getMenu($currentModule)
  {
    // Links do menu
    $links = '';

    // Foreach dos módulos
    foreach (self::$modules as $hash => $module) {
      $links .= View::render('admin/menu/link', [
        'label' => $module['label'],
        'link' => $module['link'],
        'current' => $hash == $currentModule ? 'text-danger' : ''
      ]);
    }

    // retorna a renderização do menu
    return View::render('admin/menu/box', [
      'links' => $links
    ]);
  }

  /**
   * Método responsável por renderizar a view do painel com conteudos dinamicos
   * @param string $title
   * @param string $content
   * @param string $currentModule
   * @return string
   */
  public static function getPanel($title, $content, $currentModule)
  {
    // Conteúdo do painel
    $contentPanel = View::render('admin/panel', [
      'menu' => self::getMenu($currentModule),
      'content' => $content,
    ]);

    // Retorna a pagina do renderizada
    return self::getPage($title, $contentPanel);
  }
}
