<?php

/**
 *
 * @author vozdvin
 */
interface IAuth
{

  /**
   * Проверяет, имеет ли пользователь полный доступ к любой сущности.
   *
   * @return  boolean
   */
  static function isModerator(WebUser $account);

  /**
   * Проверяет, имеет ли пользователь полный доступ к данной сущности.
   *
   * @return  boolean
   */
  function canBeManaged(WebUser $account);

  /**
   * Проверяет, имеет ли пользователь доступ на чтение данной сущности.
   *
   * @return  boolean
   */
  function canBeObserved(WebUser $account);
}

?>
