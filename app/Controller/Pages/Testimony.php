<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{
  /**
   * Método responsável por formatar a data de um depoimento
   * @param string $date
   * @return string
   */
  public static function formatDate($date)
  {
    $datetime1 = new \DateTime($date);
    $datetime2 = new \DateTime();
    $interval = $datetime1->diff($datetime2);

    if ($interval->y > 0) {
      return $interval->format('%y ano' . ($interval->y > 1 ? 's' : '') . ' atrás');
    } elseif ($interval->m > 0) {
      return $interval->format('%m mês' . ($interval->m > 1 ? 'es' : '') . ' atrás');
    } elseif ($interval->d > 0) {
      return $interval->format('%d dia' . ($interval->d > 1 ? 's' : '') . ' atrás');
    } elseif ($interval->h > 0) {
      return $interval->format('%h hora' . ($interval->h > 1 ? 's' : '') . ' atrás');
    } elseif ($interval->i > 0) {
      return $interval->format('%i minuto' . ($interval->i > 1 ? 's' : '') . ' atrás');
    } else {
      return 'agora mesmo';
    }
  }

  /**
   * Método responsável por obter a renderização dos itens de depoimentos para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getTestimonyItems($request, &$obPagination)
  {
    // Depoimentos
    $itens = '';

    // Quantidade total de depoimentos
    $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    // Página atual
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    // Instância de paginação
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 2);

    // Resultados da busca
    $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

    // Renderiza o item
    while ($obTestimony = $results->fetchObject(EntityTestimony::class)) {
      // View de depoimentos
      $itens .= View::render('pages/testimony/item', [
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem,
        'data' => self::formatDate($obTestimony->data)
      ]);
    }

    // Retorna os depoimentos
    return $itens;
  }

  /**
   * Método responsável por retornar o conteúdo (view) de depoimentos
   * @param Request $request
   * @return string
   */
  public static function getTestimonies($request)
  {
    // View de depoimentos
    $content = View::render('pages/testimonies', [
      'itens' => self::getTestimonyItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination)
    ]);

    // Retorna a view da página
    return parent::getPage('Depoimentos > WDEV', $content);
  }

  /**
   * Método responsável por cadastrar um depoimento
   * @param Request $request
   * @return string
   */
  public static function insertTestimony($request)
  {
    // Dados do POST
    $postVariables = $request->getPostVars();

    // Nova instância de depoimento
    $obTestimony = new EntityTestimony;
    $obTestimony->nome = $postVariables['nome'] ?? '';
    $obTestimony->mensagem = $postVariables['mensagem'] ?? '';
    $obTestimony->cadastrar();

    // Retorna a página de listagem de depoimentos
    return self::getTestimonies($request);
  }
}
