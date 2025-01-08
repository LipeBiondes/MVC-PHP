<?php

namespace App\Utils;

class View
{

  /**
   * Variáveis padrões da view
   * @var array
   */
  private static $variables = [];

  /**
   * Variáveis padrões da view
   * @var array
   */
  public static function init($variables = [])
  {
    self::$variables = $variables;
  }

  /**
   * Método responsável por retornar o conteúdo de uma view
   * @param string $view
   * @return string
   */
  private static function getContentView($view)
  {
    // Conteúdo do arquivo
    $file = __DIR__ . '/../../resources/view/' . $view . '.html';

    // Verifica se o arquivo existe
    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * Método responsável por retornar o conteúdo renderizado de uma view
   * @param string $view
   * @param array $variables (string/numeric)
   * @return string
   */
  public static function render($view, $variables = [])
  {
    // Conteúdo da view
    $contentView = self::getContentView($view);

    // Merge de variáveis da view
    $variables = array_merge(self::$variables, $variables);

    // Chaves do array de variáveis
    $keys = array_keys($variables);
    $keys = array_map(function ($item) {
      return '{{' . $item . '}}';
    }, $keys);

    // Retorna a view renderizada
    return str_replace($keys, array_values($variables), $contentView);
  }
}
