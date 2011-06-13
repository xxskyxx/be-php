<?php

class myUser extends sfBasicSecurityUser
{

  /**
   * Возвращает пользователя активного сеанса, если он залогинился и существует
   * 
   * @return  WebUser  
   */
  public function getSessionWebUser()
  {
    if (!$this->isAuthenticated())
    {
      return false;
    }
    if ($this->hasAttribute('id', 0))
    {
      return WebUser::byId($this->getAttribute('id'));
    }
    return false;
  }

}
