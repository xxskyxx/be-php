<?php

/**
 * moderation actions.
 *
 * @package    sf
 * @subpackage moderation
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class moderationActions extends myActions
{

  public function executeShow(sfWebRequest $request)
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
        //Пользователь не модерирует ни одну игру, нужна заглушка пустой коллекцией.
        $this->_gamesUnderModeration = new Doctrine_Collection('Game');
      }
    }

    $this->_isFullArticleModer = $this->sessionWebUser->can(Permission::ARTICLE_MODER, 0);
    if ( ! $this->_isFullArticleModer)
    {
      $articleModerationPermissions = Doctrine::getTable('GrantedPermission')
          ->createQuery('gp')->select('gp.filter_id')
          ->where('web_user_id = ?', $this->sessionWebUser->id)
          ->andWhere('permission_id = ?', Permission::ARTICLE_MODER)
          ->andWhere('deny <= 0')
          ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
      $articleModerationIds = array();
      foreach ($articleModerationPermissions as $value)
      {
        array_push($articleModerationIds, $value['filter_id']);
      }
      if (count($articleModerationIds) > 0)
      {
        $this->_teamsUnderModeration = Doctrine::getTable('Article')
            ->createQuery('a')->select()
            ->whereIn('a.id', $articleModerationIds)
            ->execute();
      }
      else
      {
        //Пользователь не модерирует ни одну статью, нужна заглушка пустой коллекцией.
        $this->_articlesUnderModeration = new Doctrine_Collection('Team');
      }
    }

    $hasSomeModerRights = $this->_isAdmin
        || $this->_isFullTeamModer
        || ($this->_teamsUnderModeration->count() > 0)
        || $this->_isFullGameModer
        || ($this->_gamesUnderModeration->count() > 0)
        || $this->_isFullArticleModer
        || ($this->_articlesUnderModeration->count() > 0);

    if ( ! $hasSomeModerRights)
    {
      $this->errorRedirect('У Вас нет полномочий модератора.');
    }
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->errorRedirectIf( ! $this->sessionWebUser->canExact(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'редактировать системные настройки'));
    $system_settings = SystemSettings::getInstance();
    $this->form = new SystemSettingsForm($system_settings);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $system_settings = SystemSettings::getInstance();
    $this->form = new SystemSettingsForm($system_settings);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $this->errorRedirectIf( ! $this->sessionWebUser->canExact(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'редактировать системные настройки'));
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $system_settings = $form->save();
      $this->successRedirect('Системные настройки успешно сохранены.', 'moderation/show');
    }
    else
    {
      $this->errorMessage('Сохранить системные настройки не удалось. Исправьте ошибки и попробуйте снова.');
    }
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
        $this->errorRedirect('Не удается соединиться с SMTP-сервером. Проверьте настройки имени SMTP-сервера, номера порта и способа шифрования.', 'moderation/show');
      }
      else
      {
        $message = Swift_Message::newInstance('Тестирование почты '.$settings->site_name)
            ->setFrom(array($settings->notify_email_addr => $settings->site_name))
            ->setTo($settings->contact_email_addr)
            ->setBody(
              "Тестирование отправки уведомлений"
            );
        if (Utils::sendEmailSafe($message, $mailer))
        {
          $this->successRedirect('Тестовое уведомление успешно отправлено на '.$settings->contact_email_addr.'.', 'moderation/show');
        }
        else
        {
          $this->errorRedirect('Соединение с SMTP-сервером установлено, но отправка тестового письма не удалась. Проверьте корректность обратого адреса, аккаунта и логина SMTP-сервера.', 'moderation/show');
        }
      }
    }
  }

}

?>
