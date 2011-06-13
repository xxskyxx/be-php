<?php

class authActions extends MyActions
{
  public function preExecute()
  {
    parent::preExecute();
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('home/index');
  }

  public function executeLogin(sfWebRequest $request)
  {
    if ($this->session->isAuthenticated())
    {
      $this->errorRedirect('Вход повторно невозможен. Вы уже авторизованы. Сначала выйдите.');
    }

    $this->form = new AuthLoginForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('auth'));
      if ($this->form->isValid())
      {
        $account = $this->form->getValues();
        $webUser = WebUser::byName($account['login']);
        if ($webUser)
        {
          if ($webUser->getPwdHash() == WebUser::getSaltedPwdHash($account['password']))
          {
            if ($webUser->getIsEnabled())
            {
              $this->session->setAttribute('login', $webUser->getLogin());
              $this->session->setAttribute('id', $webUser->getId());
              $this->session->setAuthenticated(true);
              $this->successRedirect('Вход выполнен. Добро пожаловать!');
            }
            else
            {
              $this->errorRedirect('Вход не удался. Учетная запись отключена.', 'auth/activateManual');
            }
          }
          else
          {
            $this->errorMessage('Вход не удался. Такое сочетание пользователя и пароля неизвестно.');
          }
        }
        else
        {
          $this->errorMessage('Вход не удался. Такое сочетание пользователя и пароля неизвестно.');
        }
      }
      else
      {
        $this->errorMessage('Вход не удался. Пожалуйста, исправьте ошибки и попробуйте снова.');
      }
    }
  }

  public function executeRegister(sfWebRequest $request)
  {
    if ($this->session->isAuthenticated())
    {
      $this->session->errorRedirect('Регистрация невозможна. Сначала выйдите.');
    }

    $this->form = new AuthRegisterForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('register'));
      if ($this->form->isValid())
      {
        $register = $this->form->getValues();
        $webUser = WebUser::byName($register['login']);
        if ($webUser)
        {
          $this->errorMessage('Регистрация не удалась. Пользователь '.$register['login'].' уже существует. Придумайте другое имя и попробуйте снова.');
          return;
        }

        $webUser = new WebUser;
        $webUser->setLogin($register['login']);
        $webUser->setFullName($register['login']);
        $webUser->setPwdHash(WebUser::getSaltedPwdHash($register['password']));
        $webUser->setEmail($register['email']);
        $webUser->newActivationKey();
        $webUser->grantDefault();
        $webUser->save();

        $this->successRedirect('Вы успешно зарегистрированы. Активируйте учетную запись.', 'auth/activateManual');
      }
      else
      {
        $this->errorMessage('Регистрация не удалась. Пожалуйста, исправьте ошибки и попробуйте снова.');
      }
    }
  }

  public function executeLogout(sfWebRequest $request)
  {
    $this->session->clearCredentials();
    $this->session->getAttributeHolder()->clear();
    $this->session->setAuthenticated(false);
    $this->redirect('home/index');
  }

  public function executeChangePassword(sfWebRequest $request)
  {
    if (!$this->session->isAuthenticated())
    {
      $this->errorRedirect('Для смены пароля вы должны быть авторизованы. Сначала войдите.', 'auth/login');
    }

    $this->form = new AuthChangePwdForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('changepassword'));
      if ($this->form->isValid())
      {
        $formValues = $this->form->getValues();
        $webUser = WebUser::byId($this->session->getAttribute('id'));
        if (($webUser->getPwdHash() == WebUser::getSaltedPwdHash($formValues['current'])))
        {
          $webUser->setPwdHash(WebUser::getSaltedPwdHash($formValues['new']));
          $webUser->save();
          $this->successRedirect('Пароль успешно изменен.');
        }
        else
        {
          $this->errorMessage('Изменение пароля не удалась. Неверно указан текущий пароль.');
        }
      }
      else
      {
        $this->errorMessage('Изменение пароля не удалась. Пожалуйста, исправьте ошибки и попробуйте снова.');
      }
    }
  }

  public function executeActivateManual(sfWebRequest $request)
  {
    if ($this->session->isAuthenticated())
    {
      $this->errorRedirect('Вы уже авторизованы. Для активации учетной записи сначала выйдите.');
    }

    $this->form = new AuthActivateForm();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('authactivate'));
      if ($this->form->isValid())
      {
        $formData = $this->form->getValues();
        $login = $formData['login'];
        $key = $formData['key'];
        if (is_string($res = WebUser::activate($login, $key)))
        {
          $this->errorRedirect('Активация учетной записи не удалась: '.$res, 'home/index');
        }

        if ($res)
        {
          $this->successRedirect('Ваша учетная запись активирована. Можете входить.', 'auth/login');
        }
        else
        {
          $this->errorMessage('Активация учетной записи не удалась: ключ активации указан неверно.');
        }
      }
      else
      {
        $this->errorMessage('Активация учетной записи не удалась. Пожалуйста, исправьте ошибки и попробуйте снова.');
      }
    }
  }

  public function executeActivate(sfWebRequest $request)
  {
    if ($this->session->isAuthenticated())
    {
      $this->errorRedirect('Вы уже авторизованы. Для активации учетной записи сначала выйдите.');
    }

    $login = $request->getParameter('login', '');
    $key = $request->getParameter('key', '');

    if (is_string($res = WebUser::activate($login, $key)))
    {
      $this->errorRedirect('Активация учетной записи не удалась: '.$res, 'home/index');
    }

    if ($res)
    {
      $this->successRedirect('Ваша учетная запись активирована. Можете входить.', 'auth/login');
    }
    else
    {
      $this->errorRedirect('Активация учетной записи не удалась: ключ активации указан неверно.', 'home/index');
    }
  }
}
