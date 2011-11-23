<?php

class authActions extends MyActions
{
  public function preExecute()
  {
    parent::preExecute();
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->redirectSafe('home/index');
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
          if ($webUser->getPwdHash() == Utils::saltedPwdHash($account['password']))
          {
            if ($webUser->is_enabled)
            {
              $this->session->setAttribute('login', $webUser->getLogin());
              $this->session->setAttribute('id', $webUser->getId());
              $this->session->setAttribute('region_id', $webUser->getRegionSafe()->id);
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
            $this->errorMessage('Вход не удался. Такое сочетание имени и пароля неизвестно.');
          }
        }
        else
        {
          $this->errorMessage('Вход не удался. Такое сочетание имени и пароля неизвестно.');
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
        $formData = $this->form->getValues();
        if (WebUser::byName($formData['login']) !== false)
        {
          $this->errorMessage('Регистрация не удалась. Пользователь '.$formData['login'].' уже существует. Придумайте другое имя и попробуйте снова.');
          return;
        }
        $loginParts = explode(' ', $formData['login']);
        if (count($loginParts) != 1)
        {
          $this->errorMessage('Регистрация не удалась. Имя должно состоять из одного слова.');
          return;
        }
        $fullNameParts = explode(' ', $formData['full_name']);
        $count = count($fullNameParts);
        if ($formData['full_name'] !== "")
        {
          if ($count < 2)
          {
            $this->errorMessage('Регистрация не удалась. В поле "ФИО" должны быть указаны хотя бы фамилия и имя.');
            return;
          }
          if ($count > 3)
          {
            $this->errorMessage('Регистрация не удалась. В поле "ФИО" должны быть указаны только фамилия, имя и отчество.');
            return;
          }
        }
        
        //Длину пароля надо проверять вручную, так как при проверке на форме он может быть непреднамеренно показан
        if (strlen($formData['password']) < WebUser::MIN_PWD_LENGTH)
        {
          $this->errorMessage('Регистрация не удалась. Пароль слишком короткий.');
          return;
        }

        $webUser = new WebUser;
        $webUser->login = $formData['login'];
        $webUser->full_name = $formData['full_name'];
        $webUser->pwd_hash = Utils::saltedPwdHash($formData['password']);
        $webUser->email = $formData['email'];
        $webUser->is_enabled = false;
        $webUser->tag = Utils::generateActivationKey();
        if (SystemSettings::getInstance()->fast_user_register)
        {
          //Быстрая регистрация
          $webUser->is_enabled = true;
          $webUser->save();
          $this->successRedirect('Вы успешно зарегистрированы. Можете входить.', 'auth/login');          
        }
        elseif (($webUser->email !== null) && ($webUser->email !== ''))
        {
          //Пользователь указал координаты, надо ему отправить письмо.
          $settings = SystemSettings::getInstance();
          $message = Swift_Message::newInstance('Регистрация на '.$settings->site_name)
              ->setFrom(array($settings->notify_email_addr => $settings->site_name))
              ->setTo($webUser->email)
              ->setBody(
                   "Здравствуйте!\n\n"
                  ."Вы получили это письмо, так как зарегистрировались на сайте ".$settings->site_name.".\n"
                  ."Если Вы не регистрировались на указанном сайте, просто проигнорируйте это письмо.\n\n"
                  ."Для активации Вашей учетной записи перейдите по указанной ссылке:\n"
                  ."http://".$settings->site_domain."/auth/activate?login=".$webUser->login."&key=".$webUser->tag."\n\n"
                  ."Не отвечайте на это письмо! Оно было отправлено почтовым роботом.\n"
                  ."Для связи с администрацией сайта используйте адрес ".$settings->contact_email_addr
              );
          
          if (Utils::sendEmailSafe($message, Utils::getReadyMailer()))
          {
            $webUser->save();
            $this->successRedirect('Вы успешно зарегистрированы. Активируйте учетную запись.', 'auth/activateManual');
          }
          else
          {
            $this->errorMessage('Регистрация не удалась. Не удается отправить письмо с активационным ключем. Обратитесь к администрации.');
          }
        }
        else
        {
          //Пользователь не указал координаты, пусть прыгает с активацией как хочет.
          $webUser->save();
          $this->successRedirect('Вы успешно зарегистрированы. Активируйте учетную запись.', 'auth/activateManual');
        }
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
    $this->redirectSafe('home/index');
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
        if (($webUser->getPwdHash() != Utils::saltedPwdHash($formValues['current'])))
        {
          $this->errorMessage('Изменить пароль не удалось: неверно указан текущий пароль.');
        }
        //Длину пароля надо проверять вручную, так как при проверке на форме он может быть непреднамеренно показан
        if (strlen($formValues['new']) < WebUser::MIN_PWD_LENGTH)
        {
          $this->errorMessage('Изменить пароль не удалось: новый пароль слишком короткий.');
          return;
        }
        
        $webUser->setPwdHash(Utils::saltedPwdHash($formValues['new']));
        $webUser->save();
        $this->successRedirect('Пароль успешно изменен.');
      }
      else
      {
        $this->errorMessage('Изменить пароль не удалось. Пожалуйста, исправьте ошибки и попробуйте снова.');
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
  
  public function executeCreateTeam(sfWebRequest $request)
  {
    $retUrl = ($this->session->isAuthenticated()) ? 'team/index' : 'home/index';
    $settings = SystemSettings::getInstance();
    $key = $request->getParameter('key', '');
    $this->errorRedirectUnless(
        $teamCreateRequest = TeamCreateRequest::byId($request->getParameter('id')),
        'Заявка на создание команды не найдена',
        $retUrl
    );
    if ( ! $settings->email_team_create)
    {
      $this->errorRedirect(
          'Почтовое подтверждение создания команд сейчас не разрешено.',
          $retUrl
      );
    }
    $this->errorRedirectUnless(
        Utils::byField('Team', 'name', $teamCreateRequest->name) === false,
        'Не удалось создать команду: команда '.$teamCreateRequest->name.' уже существует.',
        $retUrl
    );
    
    if (strcmp($key, $teamCreateRequest->tag) == 0)
    {
      $team = TeamCreateRequest::doCreate($teamCreateRequest);
      $this->successRedirect(
          'Команда '.$team->name.' успешно создана.',
          $retUrl
      );    
    }
    else
    {
      $this->errorRedirect(
          'Создание команды не удалось: неверный ключ подтверждения.',
          $retUrl
      );
    }

  }

  public function executeCreateGame(sfWebRequest $request)
  {
    $retUrl = ($this->session->isAuthenticated()) ? 'game/index' : 'home/index';
    $settings = SystemSettings::getInstance();
    $key = $request->getParameter('key', '');
    $this->errorRedirectUnless(
        $gameCreateRequest = GameCreateRequest::byId($request->getParameter('id')),
        'Заявка на создание игры не найдена',
        $retUrl
    );
    if ( ! $settings->email_game_create)
    {
      $this->errorRedirect(
          'Почтовое подтверждение создания игр сейчас не разрешено.',
          $retUrl
      );
    }
    $this->errorRedirectUnless(
        Utils::byField('Team', 'name', $gameCreateRequest->name) === false,
        'Не удалось создать игру: игра '.$gameCreateRequest->name.' уже существует.',
        $retUrl
    );
    
    if (strcmp($key, $gameCreateRequest->tag) == 0)
    {
      $team = GameCreateRequest::doCreate($gameCreateRequest);
      $this->successRedirect(
          'Игра '.$team->name.' успешно создана.',
          $retUrl
      );    
    }
    else
    {
      $this->errorRedirect(
          'Создание игры не удалось: неверный ключ подтверждения.',
          $retUrl
      );
    }

  }
  
}
