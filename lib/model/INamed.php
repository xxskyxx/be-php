<?php

/**
 * Методы класса, который однозначно определяется по какому-либо имени.
 *
 * @author vozdvin
 */
interface INamed
{

  /**
   * Возвращает имя экземпляра.
   *
   * @return  string
   */
  function getInnerName();

  /**
   * Возвращает экземпляр с указанным именем.
   *
   * @param   string            $name   Имя искомого элемента
   * @return  Doctrine_Record           Или false, если нет результатов.
   */
  static function byName($name);
}

?>
