<?php

namespace App\Controller\Admin;

use App\Model\Entity\User;
use App\Utils\View;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page
{
  /**
   * Método responsável por retornar a renderização da página de login
   * @param Request $request
   * @param string $errorMessage
   * @return string
   */
  public static function getLogin($request, $errorMessage = null)
  {
    // Status de login
    $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

    // Conteúdo da página de login
    $content = View::render('admin/login', [
      'status' => $status
    ]);

    // Retorna a página completa
    return parent::getPage('Login > Wdev', $content);
  }

  /**
   * Método responsável por definir o login do usuário
   * @param Request $request
   */
  public static function setLogin($request)
  {
    // Dados do POST
    $postVars = $request->getPostVars();
    $email = $postVars['email'] ?? '';
    $password = $postVars['password'] ?? '';

    // Busca o usuário pelo e-mail
    $obUser = User::getUserByEmail($email);

    // Verifica se o email e senha são válidos
    if (!$obUser instanceof User || !password_verify($password, $obUser->senha)) {
      return self::getLogin($request, 'E-mail ou senha inválidos');
    }

    // Cria sessão de login
    SessionAdminLogin::login($obUser);

    // Redireciona o usuario para a home admin
    $request->getRouter()->redirect('/admin');
  }

  /**
   * Método responsavél por deslogart o usuario
   * @param Request $request
   */
  public static function setLogout($request)
  {

    // Destrói a sessão de login
    SessionAdminLogin::logout();

    // Redireciona o usuario para a pagina de login
    $request->getRouter()->redirect('/admin/login');
  }
}
