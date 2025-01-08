<?php

namespace App\Session\Admin;

class Login
{
  /**
   * Método responsavel por iniciar a sessão
   */
  private static function init()
  {
    // Verifica se a sessão não está ativa
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  /**
   * Método responsavel por criar o login do usuario
   * @param User $obUser
   * @return boolean
   */
  public static function login($obUser)
  {
    // Inicia a sessão
    self::init();

    // Define a sessão do usuario
    $_SESSION['admin']['usuario'] = [
      'id' => $obUser->id,
      'nome' => $obUser->nome,
      'email' => $obUser->email
    ];

    return true;
  }

  /**
   * Método responsável por verificar se o usuario está logado
   * @return boolean 
   */
  public static function isLogged()
  {
    // Inicia a sessão
    self::init();

    // Retorna a verificação
    return isset($_SESSION['admin']['usuario']['id']);
  }

  /**
   * Método responsavél por execultar o logout do usuário
   * @return boolean
   */
  public static function logout()
  {
    // Inicia a sessão
    self::init();

    // Desloga o usuário
    unset($_SESSION['admin']['usuario']);

    // Sucesso
    return true;
  }
}
