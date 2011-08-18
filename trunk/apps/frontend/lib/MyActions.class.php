<?php

class MyActions extends sfActions
{
  /**
   * @var MyUser
   */
  public $session;
  /**
   * @var WebUser
   */
  public $sessionWebUser;
  /**
   * @var string
   */
  public $retUrlRaw;
  /**
   * @var string
   */
  public $retUrlDecoded;

  public function preExecute()
  {
    $this->session = $this->getUser();
    $this->sessionWebUser = $this->session->getSessionWebUser();
    $this->retUrlRaw = $this->getRequestParameter('returl', '');
    $this->retUrlDecoded = Utils::getReturnUrl($this->getRequest(), '');
  }

  /**
   * Простановка информационного сообщения без перенаправления.
   *
   * @param   string  $message  Сообщение
   */
  protected function successMessage($message)
  {
    $this->session->setFlash('notice', $message, false);
  }

  /**
   * Выполняет перенаправление с простановкой информационного сообщения.
   * Если адрес не указан, то пытается получить его из retUrl-параметров.
   *
   * @param   string  $message  Сообщение
   * @param   string  $target   Адрес перенаправления
   */
  protected function successRedirect($message, $target = '')
  {
    if ($target == '')
    {
      $target = $this->retUrlDecoded;
    }
    if ($target == '')
    {
      $target = 'home/index';
    }
    $this->session->setFlash('notice', $message);
    $this->redirect($target);
  }
  
  /**
   * Выполняет перенаправление с простановкой предупреждающего сообщения.
   * Если адрес не указан, то пытается получить его из retUrl-параметров.
   *
   * @param   string  $message  Сообщение
   * @param   string  $target   Адрес перенаправления
   */
  protected function warningRedirect($message, $target = '')
  {
    if ($target == '')
    {
      $target = $this->retUrlDecoded;
    }
    if ($target == '')
    {
      $target = 'home/index';
    }
    $this->session->setFlash('warning', $message);
    $this->redirect($target);
  }

  /**
   * Простановка сообщения об ошибке без перенаправления.
   *
   * @param   string  $message  Сообщение
   */
  protected function errorMessage($message)
  {
    $this->session->setFlash('error', $message, false);
  }

  /**
   * Выполняет перенаправление с простановкой сообщения об ошибке.
   * Если адрес не указан, то пытается получить его из retUrl-параметров.
   *
   * @param   string  $message  Сообщение
   * @param   string  $target   Адрес перенаправления
   */
  protected function errorRedirect($message, $target = '')
  {
    if ($target == '')
    {
      $target = $this->retUrlDecoded;
    }
    if ($target == '')
    {
      $target = 'home/index';
    }
    $this->session->setFlash('error', $message);
    $this->redirect($target);
  }

  /**
   * Выполняет перенаправление с простановкой сообщения об ошибке если условие выполнено
   *
   * @param   boolean   $condition  Сообщение
   * @param   string    $message    Сообщение
   * @param   string    $target     Адрес перенаправления
   */
  protected function errorRedirectIf($condition, $message, $target = '')
  {
    if ($condition)
    {
      $this->errorRedirect($message, $target);
    }
  }

  /**
   * Выполняет перенаправление с простановкой сообщения об ошибке если условие не выполнено
   *
   * @param   boolean   $condition  Сообщение
   * @param   string    $message    Сообщение
   * @param   string    $target     Адрес перенаправления
   */
  protected function errorRedirectUnless($condition, $message, $target = '')
  {
    if (!$condition)
    {
      $this->errorRedirect($message, $target);
    }
  }

}

?>
