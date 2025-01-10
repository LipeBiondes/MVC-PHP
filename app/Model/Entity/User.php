<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class User
{
  /**
   * ID do usuário
   * @var integer
   */
  public $id;

  /**
   * Nome do usuário
   * @var string
   */
  public $nome;

  /**
   * E-mail do usuário
   * @var string
   */
  public $email;

  /**
   * Hash da senha do usuário
   * @var string
   */
  public $senha;

  /**
   * Método responsável por retornar um usuário com base em seu E-mail
   * @param string $email
   * @return User
   */
  public static function getUserByEmail($email)
  {
    // Retorna o usuário
    return self::getUsers('email = "' . $email . '"')->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar um usuário com base em seu ID
   * @param integer $id
   * @return User
   */
  public static function getUserById($id)
  {
    // Retorna o usuário
    return self::getUsers('id = ' . $id)->fetchObject(self::class);
  }

  /**
   * Método responsável por retornar um usuários
   * @param string $where
   * @param string $order
   * @param string $limit
   * @param string $fields
   * @return PDOStatement
   */
  public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
  {
    // Retorna os usuários
    return (new Database('usuarios'))->select($where, $order, $limit, $fields);
  }

  /**
   * Método responsável por cadastrar um novo usuário no banco
   * @return boolean
   */
  public function cadastrar()
  {
    // Insere o usuário no banco
    $this->id = (new Database('usuarios'))->insert([
      'nome' => $this->nome,
      'email' => $this->email,
      'senha' => $this->senha
    ]);

    // Sucesso
    return true;
  }

  /**
   * Método responsável por atualizar um usuário no banco
   * @return boolean
   */
  public function atualizar()
  {
    // Atualiza o usuário no banco
    return (new Database('usuarios'))->update('id = ' . $this->id, [
      'nome' => $this->nome,
      'email' => $this->email,
      'senha' => $this->senha
    ]);

    // Sucesso
    return true;
  }

  /**
   * Método responsável por excluir um usuário no banco
   * @return boolean
   */
  public function excluir()
  {
    // Exclui o usuário do banco
    return (new Database('usuarios'))->delete('id = ' . $this->id);

    // Sucesso
    return true;
  }
}
