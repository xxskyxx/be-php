<?php

class moderationActions extends MyActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $this->_settings = SystemSettings::getInstance();
    
    /* Соберем все данные о правах текущего пользователя */
    $this->_isAdmin = $this->sessionWebUser->canExact(Permission::ROOT, 0);
    
    $this->_isWebUserModer = $this->sessionWebUser->can(Permission::WEB_USER_MODER, 0);
    $this->_isPermissionModer = $this->sessionWebUser->can(Permission::PERMISSION_MODER, 0);

    $this->_isFullTeamModer = $this->sessionWebUser->can(Permission::TEAM_MODER, 0);
    if ( ! $this->_isFullTeamModer)
    {
      $teamModerationPermissions = Doctrine::getTable('GrantedPermission')
          ->createQuery('gp')->select('gp.filter_id')
          ->where('web_user_id = ?', $this->sessionWebUser->id)
          ->andWhere('permission_id = ?', Permission::TEAM_MODER)
          ->andWhere('deny <= 0')
          ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
      $teamModerationIds = array();
      foreach ($teamModerationPermissions as $value)
      {
        array_push($teamModerationIds, $value['filter_id']);
      }
      if (count($teamModerationIds) > 0)
      {
        $this->_teamsUnderModeration = Doctrine::getTable('Team')
            ->createQuery('t')->select()
            ->whereIn('t.id', $teamModerationIds)
            ->execute();
      }
      else
      {
        //Пользователь не модерирует ни одну команду, нужна заглушка пустой коллекцией.
        $this->_teamsUnderModeration = new Doctrine_Collection('Team');
      }
    }

    $this->_isFullGameModer = $this->sessionWebUser->can(Permission::GAME_MODER, 0);
    if ( ! $this->_isFullGameModer)
    {
      $gameModerationPermissions = Doctrine::getTable('GrantedPermission')
          ->createQuery('gp')->select('gp.filter_id')
          ->where('web_user_id = ?', $this->sessionWebUser->id)
          ->andWhere('permission_id = ?', Permission::GAME_MODER)
          ->andWhere('deny <= 0')
          ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
      $gameModerationIds = array();
      foreach ($gameModerationPermissions as $value)
      {
        array_push($gameModerationIds, $value['filter_id']);
      }
      if (count($gameModerationIds) > 0)
      {
        $this->_gamesUnderModeration = Doctrine::getTable('Game')
            ->createQuery('g')->select()
            ->whereIn('g.id', $gameModerationIds)
            ->execute();
      }
      else
      {
        //Пользователь не модерирует ни одну команду, нужна заглушка пустой коллекцией.
        $this->_gamesUnderModeration = new Doctrine_Collection('Game');
      }
    }    
    
    $hasSomeModerRights = $this->_isAdmin
        || $this->_isFullTeamModer
        || ($this->_teamsUnderModeration->count() > 0)
        || $this->_isFullGameModer
        || ($this->_gamesUnderModeration->count() > 0);        
    
    if ( ! $hasSomeModerRights)
    {
      $this->errorRedirect('У Вас нет полномочий модератора.');
    }
  }
  
  public function executeEditSettings(sfWebRequest $request)
  {
    //TODO: Сделать редактирование настроек сайта.
    $this->errorRedirect('Редактирование настроек сайта пока не реализовано', 'moderation/index');
  }
  
  public function executeSMTPTest(sfWebRequest $request)
  {
    if ( ! $this->sessionWebUser->canExact(Permission::ROOT, 0))
    {
      $this->errorRedirect(Utils::cannotMessage($this->sessionWebUser->login, 'тестировать отправку писем'));
    }
    else
    {
      $settings = SystemSettings::getInstance();
      $mailer = Utils::getReadyMailer();
      if ( ! $mailer)
      {
        $this->errorRedirect('Не удается соединиться с SMTP-сервером. Проверьте настройки имени SMTP-сервера, номера порта и способа шифрования.', 'moderation/index');
      }
      else
      {
        $message = Swift_Message::newInstance('Тестирование почты '.$settings->site_name)
            ->setFrom(array($settings->notify_email_addr => $settings->site_name))
            ->setTo($settings->contact_email_addr)
            ->setBody(
              "Тестирование отправки уведомлений"
            );
        $isSent = false;
        try
        {
          $isSent = Utils::getReadyMailer()->send($message);
        }
        catch (Exception $e)
        {
          $isSent = false;
        }
        if ( ! $isSent)
        {
          $this->errorRedirect('Соединение с SMTP-сервером установлено, но отправка тестового письма не удалась. Проверьте корректность обратого адреса, аккаунта и логина SMTP-сервера.', 'moderation/index');
        }
        else
        {
          $this->successRedirect('Тестовое уведомление успешно отправлено на '.$settings->contact_email_addr.'.', 'moderation/index');          
        }        
      }
    } 
  }

}

?>
