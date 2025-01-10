<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
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
    $itens = '';

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
      $itens .= View::render('admin/modules/testimonies/item', [
        'id' => $obTestimony->id,
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem,
        'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data)),
      ]);
    }

    // Retorna os depoimentos
    return $itens;
  }


  /**
   * Método responsável por retornar o conteúdo (view) da listagem de depoimentos
   * @param Request $request
   * @return string
   */
  public static function getTestimonies($request)
  {
    // Conteúdo da Testimonies
    $content = View::render('admin/modules/testimonies/index', [
      'itens' => self::getTestimonyItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request),
    ]);

    // Retorna a página completa
    return parent::getPanel('Depoimentos > Wdev', $content, 'testimonies');
  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo depoimento
   * @param Request $request
   * @return string
   */
  public static function getNewTestimony($request)
  {
    // Conteúdo do formulário de depoimentos
    $content = View::render('admin/modules/testimonies/form', [
      'title' => 'Cadastrar depoimento',
      'nome' => '',
      'mensagem' => '',
      'status' => '',
    ]);

    // Retorna a página completa
    return parent::getPanel('Cadastrar depoimento > Wdev', $content, 'testimonies');
  }

  /**
   * Método responsável por cadastrar um depoimento
   * @param Request $request
   * @return string
   */
  public static function setNewTestimony($request)
  {
    // Post Vars
    $postVars = $request->getPostVars();

    // Nova instância de depoimento
    $obTestimony = new EntityTestimony;
    $obTestimony->nome = $postVars['nome'] ?? '';
    $obTestimony->mensagem = $postVars['mensagem'] ?? '';
    $obTestimony->cadastrar();

    // Redireciona o usuário
    $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=created');
  }

  /**
   * Método responsável por retornar o status de uma ação de depoimento
   * @param Request $request
   * @return string
   */
  private static function getStatus($request)
  {
    // Query Params
    $queryParams = $request->getQueryParams();

    // Status
    if (!isset($queryParams['status'])) return '';

    // Mensagens de status
    switch ($queryParams['status']) {
      case 'created':
        return Alert::getSuccess('Depoimento criado com sucesso!');
        break;
      case 'updated':
        return Alert::getSuccess('Depoimento atualizado com sucesso!');
        break;
      case 'deleted':
        return Alert::getSuccess('Depoimento excluído com sucesso!');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de edição de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditTestimony($request, $id)
  {
    // Obtém o depoimento do banco de dados
    $obTestimony = EntityTestimony::getTestimony($id);

    // Valida a instância
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    // Conteúdo do formulário de depoimentos
    $content = View::render('admin/modules/testimonies/form', [
      'title' => 'Editar depoimento',
      'nome' => $obTestimony->nome,
      'mensagem' => $obTestimony->mensagem,
      'status' => self::getStatus($request),
    ]);

    // Retorna a página completa
    return parent::getPanel('Editar depoimento > Wdev', $content, 'testimonies');
  }

  /**
   * Método responsável por gravar a atualização de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditTestimony($request, $id)
  {
    // Obtém o depoimento do banco de dados
    $obTestimony = EntityTestimony::getTestimony($id);

    // Valida a instância
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    // Post Vars
    $postVars = $request->getPostVars();

    // Atualiza a instância
    $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
    $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
    $obTestimony->atualizar();

    // Redireciona o usuário
    $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=updated');
  }

  /**
   * Método responsável por excluir um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeleteTestimony($request, $id)
  {
    // Obtém o depoimento do banco de dados
    $obTestimony = EntityTestimony::getTestimony($id);

    // Valida a instância
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    // Conteúdo do formulário de depoimentos
    $content = View::render('admin/modules/testimonies/delete', [
      'nome' => $obTestimony->nome,
      'mensagem' => $obTestimony->mensagem,
    ]);

    // Retorna a página completa
    return parent::getPanel('Excluir um depoimento > Wdev', $content, 'testimonies');
  }

  /**
   * Método responsável por excluir um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeleteTestimony($request, $id)
  {
    // Obtém o depoimento do banco de dados
    $obTestimony = EntityTestimony::getTestimony($id);

    // Valida a instância
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    // Exclui o depoimento do banco de dados
    $obTestimony->excluir();

    // Redireciona o usuário
    $request->getRouter()->redirect('/admin/testimonies?status=deleted');
  }
}
