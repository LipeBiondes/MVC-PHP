<?php

namespace App\Model\Entity;

use PDOStatement;
use WilliamCosta\DatabaseManager\Database;

class Testimony
{
  /**
   * ID do depoimento
   * @var integer
   */
  public $id;

  /**
   * Nome do usuário que fez o depoimento
   * @var string
   */
  public $nome;

  /**
   * Mensagem do depoimento
   * @var string
   */
  public $mensagem;

  /**
   * Data de criação do depoimento
   * @var string
   */
  public $data;

  /**
   * Método responsável por retornar um depoimento com base em seu ID
   * @return boolean
   */
  public function cadastrar()
  {
    // Define a data
    $this->data = date('Y-m-d H:i:s');

    // Insere o depoimento no banco
    $this->id = (new Database('depoimentos'))->insert([
      'nome' => $this->nome,
      'mensagem' => $this->mensagem,
      'data' => $this->data
    ]);

    // Sucesso
    return true;
  }

  /**
   * Método responsável por retornar um depoimentos
   * @param string $where
   * @param string $order
   * @param string $limit
   * @param string $fields
   * @return PDOStatement
   */
  public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*')
  {
    // Retorna os depoimentos
    return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
  }

  /**
   * Método responsável por retornar um depoimento com base em seu ID
   * @param integer $id
   * @return Testimony
   */
  public static function getTestimony($id)
  {
    // Retorna o depoimento
    return self::getTestimonies('id = ' . $id)->fetchObject(self::class);
  }

  /**
   * Método responsável por atualizar um depoimento no banco
   * @return boolean
   */
  public function atualizar()
  {
    // Atualiza o depoimento no banco
    return (new Database('depoimentos'))->update('id = ' . $this->id, [
      'nome' => $this->nome,
      'mensagem' => $this->mensagem
    ]);
  }

  /**
   * Método responsável por excluir um depoimento no banco
   * @return boolean
   */
  public function excluir()
  {
    // Exclui o depoimento do banco
    return (new Database('depoimentos'))->delete('id = ' . $this->id);
  }
}
