<?php

namespace App\Controller\Api;

class Api
{
  /**
   * Método responsável por retornar os detalhes da api
   * @param Request $request
   * @return array
   */
  public static function getDetails($request)
  {
    return [
      'name' => 'API - Wdev',
      'version' => '1.0.0',
      'author' => 'Alefe Filipe',
      'email' => 'alefe@gmail.com'
    ];
  }

  /**
   * Método responsável por retornar os detalhes da paginação
   * @param Request $request
   * @param Pagination $obPagination
   * @return array
   */
  protected  static function getPagination($request, $obPagination)
  {
    // Query Params
    $queryParams = $request->getQueryParams();

    // Páginas
    $pages = $obPagination->getPages();

    // Retorno
    return [
      'paginaAtual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
      'quantidadePaginas' => !empty($pages) ? count($pages) : 1,
    ];
  }
}
