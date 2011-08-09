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
  
}

?>
