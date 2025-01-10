<?php

namespace App\Controller\Api;

use App\Model\Entity\Testimony as EntityTestimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api
{
  /**
   * Método responsável por obter a renderização dos itens de depoimentos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getTestimonyItems($request, &$obPagination)
  {
    // Depoimentos
    $itens = [];

    // Quantidade total de depoimentos
    $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    // Página atual
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    // Instância de paginação
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    // Resultados da busca
    $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

    // Renderiza o item
    while ($obTestimony = $results->fetchObject(EntityTestimony::class)) {
      // View de depoimentos
      $itens[] =  [
        'id' => (int)$obTestimony->id,
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem,
        'data' => $obTestimony->data,
      ];
    }

    // Retorna os depoimentos
    return $itens;
  }

  /**
   * Método responsável por retornar os detalhes de um depoimento
   * @param Request $request
   * @param integer $id
   * @return array
   */
  public static function getTestimony($request, $id)
  {
    // Valida o ID do depoimento
    if (!is_numeric($id)) {
      throw new \Exception("O ID {$id} não é válido", 400);
    }

    // Busca o depoimento pelo ID
    $obTestimony = EntityTestimony::getTestimonyById($id);

    // Verifica se o depoimento existe
    if (!$obTestimony instanceof EntityTestimony) {
      throw new \Exception("O depoimento {$id} não foi encontrado", 404);
    }

    // Retorna os detalhes do depoimento
    return [
      'id' => (int)$obTestimony->id,
      'nome' => $obTestimony->nome,
      'mensagem' => $obTestimony->mensagem,
      'data' => $obTestimony->data,
    ];
  }

  /**
   * Método responsável por retornar os depoimentos cadastrados
   * @param Request $request
   * @return array
   */
  public static function getTestimonies($request)
  {
    return [
      'depoimentos' => self::getTestimonyItems($request, $obPagination),
      'paginacao' => parent::getPagination($request, $obPagination)
    ];
  }
}
