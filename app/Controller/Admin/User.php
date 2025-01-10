<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use WilliamCosta\DatabaseManager\Pagination;

class User extends Page
{

  /**
   * Método responsável por obter a renderização dos itens de usuarios para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getUserItems($request, &$obPagination)
  {
    // usuarios
    $itens = '';

    // Quantidade total de usuarios
    $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    // Página atual
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    // Instância de paginação
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    // Resultados da busca
    $results = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());

    // Renderiza o item
    while ($obUser = $results->fetchObject(EntityUser::class)) {
      // View de usuarios
      $itens .= View::render('admin/modules/users/item', [
        'id' => $obUser->id,
        'nome' => $obUser->nome,
        'email' => $obUser->email,
      ]);
    }

    // Retorna os usuarios
    return $itens;
  }


  /**
   * Método responsável por retornar o conteúdo (view) da listagem de usuarios
   * @param Request $request
   * @return string
   */
  public static function getUsers($request)
  {
    // Conteúdo da Testimonies
    $content = View::render('admin/modules/users/index', [
      'itens' => self::getUserItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request),
    ]);

    // Retorna a página completa
    return parent::getPanel('Usuarios > Wdev', $content, 'users');
  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo usuario
   * @param Request $request
   * @return string
   */
  public static function getNewUser($request)
  {
    // Conteúdo do formulário de usuarios
    $content = View::render('admin/modules/users/form', [
      'title' => 'Cadastrar usuario',
      'nome' => '',
      'email' => '',
      'status' => self::getStatus($request),
    ]);

    // Retorna a página completa
    return parent::getPanel('Cadastrar usuario > Wdev', $content, 'users');
  }

  /**
   * Método responsável por cadastrar um usuario no banco
   * @param Request $request
   * @return string
   */
  public static function setNewUser($request)
  {
    // Post Vars
    $postVars = $request->getPostVars();
    $nome = $postVars['nome'] ?? '';
    $email = $postVars['email'] ?? '';
    $senha = $postVars['senha'] ?? '';

    // Valida se o email do usuario
    $obUser = EntityUser::getUserByEmail($email);

    // Valida a instância
    if ($obUser instanceof EntityUser) {
      // Redireciona o usuário
      $request->getRouter()->redirect('/admin/users/new?status=duplicated');
    }

    // Nova instância de usuario
    $obUser = new EntityUser;
    $obUser->nome = $nome;
    $obUser->email = $email;
    $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
    $obUser->cadastrar();

    // Redireciona o usuário
    $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=created');
  }

  /**
   * Método responsável por retornar o status de uma ação de usuario
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
        return Alert::getSuccess('Usuário criado com sucesso!');
        break;
      case 'updated':
        return Alert::getSuccess('Usuário atualizado com sucesso!');
        break;
      case 'deleted':
        return Alert::getSuccess('Usuário excluído com sucesso!');
        break;
      case 'duplicated':
        return Alert::getError('E-mail indisponível para uso!');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de edição de um usuario
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditUser($request, $id)
  {
    // Obtém o usuario do banco de dados
    $obUser = EntityUser::getUserById($id);

    // Valida a instância
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    // Conteúdo do formulário de usuarios
    $content = View::render('admin/modules/users/form', [
      'title' => 'Editar usuario',
      'nome' => $obUser->nome,
      'email' => $obUser->email,
      'status' => self::getStatus($request),
    ]);

    // Retorna a página completa
    return parent::getPanel('Editar usuário > Wdev', $content, 'users');
  }

  /**
   * Método responsável por gravar a atualização de um usuario
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditUser($request, $id)
  {
    // Obtém o usuario do banco de dados
    $obUser = EntityUser::getUserById($id);

    // Valida a instância
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    // Post Vars
    $postVars = $request->getPostVars();
    $nome = $postVars['nome'] ?? '';
    $email = $postVars['email'] ?? '';
    $senha = $postVars['senha'] ?? '';

    // Valida se o email do usuario é duplicado
    $obUserEmail = EntityUser::getUserByEmail($email);
    if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $id) {
      // Redireciona o usuário
      $request->getRouter()->redirect('/admin/users/' . $id . '/edit?status=duplicated');
    }

    // Atualiza a instância
    $obUser->nome = $nome;
    $obUser->email = $email;
    $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
    $obUser->atualizar();

    // Redireciona o usuário
    $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=updated');
  }

  /**
   * Método responsável por excluir um usuario
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeleteUser($request, $id)
  {
    // Obtém o usuario do banco de dados
    $obUser = EntityUser::getUserById($id);

    // Valida a instância
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    // Conteúdo do formulário de usuarios
    $content = View::render('admin/modules/users/delete', [
      'nome' => $obUser->nome,
      'email' => $obUser->email,
    ]);

    // Retorna a página completa
    return parent::getPanel('Excluir usuario > Wdev', $content, 'users');
  }

  /**
   * Método responsável por excluir um usuario
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeleteUser($request, $id)
  {
    // Obtém o usuario do banco de dados
    $obUser = EntityUser::getUserById($id);

    // Valida a instância
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    // Exclui o usuario do banco de dados
    $obUser->excluir();

    // Redireciona o usuário
    $request->getRouter()->redirect('/admin/users?status=deleted');
  }
}
