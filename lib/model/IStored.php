<?php

/**
 * Методы класса, проистекающие из факта того, что его экземпляры хранятся в БД.
 * Являются в основном сокращениями Doctrine::методов.
 *
 * @author vozdvin
 */
interface IStored
{

  /**
   * Возвращает все известные экземпляры.
   *
   * @return  Doctrine_Collection   Или false, если нет результатов.
   */
  static function all();

  /**
   * Возвращает все экземпляры сущности, у которых ключ равен заданному.
   *
   * @param   string            $id   Ключ искомого элемента
   * @return  Doctrine_Record         Или false, если нет результатов.
   */
  static function byId($id);
}

?>
