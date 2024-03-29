<?php

/**
 * game actions.
 *
 * @package    sf
 * @subpackage game
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class gameActions extends MyActions
{

  public function preExecute()
  {
    parent::preExecute();
    $this->retUrl = $this->retUrlRaw;
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->errorRedirectIf(
        $this->sessionWebUser->cannot(Permission::GAME_INDEX, 0),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать список игр')
    );

    $this->_currentRegion = Region::byIdSafe($this->session->getAttribute('region_id'));    

    $this->_retUrlRaw = Utils::encodeSafeUrl('game/index');
    $this->_sessionIsGameModerator = Game::isModerator($this->sessionWebUser);

    $this->_plannedGames = new Doctrine_Collection('Game');
    $this->_activeGames = new Doctrine_Collection('Game');
    $this->_archivedGames = new Doctrine_Collection('Game');
    $this->_sessionPlayIndex = array();
    $this->_sessionIsActorIndex = array();

    if ($this->_currentRegion->id == Region::DEFAULT_REGION)
    {    
      $games = Doctrine::getTable('Game')
          ->createQuery('g')->leftJoin('g.teamStates')
          ->select()->orderBy('g.name')
          ->execute();
    }
    else
    {
      $games = Doctrine::getTable('Game')
          ->createQuery('g')->leftJoin('g.teamStates')
          ->select()->orderBy('g.name')
          ->where('region_id = ?', $this->_currentRegion->id)
          ->execute();      
    }
    
    foreach ($games as $game)
    {
      switch ($game->status)
      {
        case Game::GAME_PLANNED:
          $this->_plannedGames->add($game);
          break;
        case Game::GAME_VERIFICATION:
        case Game::GAME_READY:
        case Game::GAME_STEADY:
        case Game::GAME_ACTIVE:
        case Game::GAME_FINISHED:
          $this->_activeGames->add($game);
          break;
        case Game::GAME_ARCHIVED:
          $this->_archivedGames->add($game);
          break;
        default:
          $this->_plannedGames->add($game);
          break;
      }
      $this->_sessionPlayIndex[$game->id] = $game->isPlayerRegistered($this->sessionWebUser);
      $this->_sessionIsActorIndex[$game->id] = $game->isActor($this->sessionWebUser);
    }

    $gameCreateRequests = Doctrine::getTable('GameCreateRequest')
        ->createQuery('gcr')
        ->select()->orderBy('gcr.name')
        ->execute();
    if ($this->_sessionIsGameModerator)
    {
      $this->_gameCreateRequests = $gameCreateRequests;
    }
    else
    {
      $this->_gameCreateRequests = new Doctrine_Collection('GameCreateRequest');
      foreach ($gameCreateRequests as $gameCreateRequest)
      {
        if ($gameCreateRequest->Team->canBeManaged($this->sessionWebUser))
        {
          $this->_gameCreateRequests->add($gameCreateRequest);
        }
      }
    }

  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->_game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    if ($this->_game->canBeObserved($this->sessionWebUser))
    {
      $this->_tab = $request->getParameter('tab', 'props');
      $this->_sessionCanManage = $this->_game->isManager($this->sessionWebUser);
      $this->_sessionIsModerator = $this->sessionWebUser->can(Permission::GAME_MODER, $this->_game->id);
      if      ($this->_tab == 'props')
      {
        //
      }
      else if ($this->_tab == 'teams')
      {
        $this->_teamStates = Doctrine::getTable('TeamState')
            ->createQuery('ts')->innerJoin('ts.Team')
            ->select()->where('game_id = ?', $this->_game->id)
            ->orderBy('ts.Team.name')->execute();
        $this->_gameCandidates = Doctrine::getTable('GameCandidate')
            ->createQuery('gc')->innerJoin('gc.Team')
            ->select()->where('game_id = ?', $this->_game->id)
            ->orderBy('gc.Team.name')->execute();
      }
      else if ($this->_tab == 'tasks')
      {
        $this->_tasks = Doctrine::getTable('Task')
            ->createQuery('t')->leftJoin('t.taskConstraints')->leftJoin('t.taskTransitions')
            ->select()->where('game_id = ?', $this->_game->id)
            ->orderBy('t.name')->execute();
      }
    }
    else
    {
      $this->forward('game', 'info');
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->errorRedirectUnless(Game::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать игру'));
    $this->form = new GameForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->errorRedirectUnless(Game::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать игру'));
    $this->form = new gameForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $this->errorRedirectUnless($this->game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'редактировать игру'));
    $this->form = new GameForm($this->game);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $this->form = new GameForm($this->game);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $this->errorRedirectUnless(Game::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять игру'));
    $this->game->delete();
    $this->successRedirect('Игра успешно удалена.', 'game/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять игру'));
      if ( ! (    (Timing::strToDate($object->start_briefing_datetime) <= Timing::strToDate($object->start_datetime))
               && (Timing::strToDate($object->start_datetime) <= Timing::strToDate($object->stop_datetime))
               && (Timing::strToDate($object->stop_datetime) <= Timing::strToDate($object->finish_briefing_datetime)) ) )
      {
        $this->errorMessage('Сохранить игру не удалось. Даты брифинга, начала, остановки, и подведения итогов игры стоят в неправильном порядке.');
      }
      if ( (Timing::strToDate($object->stop_datetime) - Timing::strToDate($object->start_datetime)) < $object->time_per_game*60 )
      {
        $this->errorMessage('Сохранить игру не удалось. Даты начала и остановки игры должны отстоять друг от друга не менее, чем на отведенное на игру время.');
      }
      else
      {
        $object->initDefaults();
        $object->save();
        $this->successRedirect('Игра '.$object->name.' успешно сохранена.', 'game/show?id='.$object->id);
      }
    }
    else
    {
      $this->errorMessage('Сохранить игру не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

  public function executeInfo(sfWebRequest $request)
  {
    $this->forward404Unless($this->_game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $teamList = $this->_game->getTeamsAvailableToPostJoinBy($this->sessionWebUser);
    $this->_canPostJoin = $teamList->count() > 0;
  }

  public function executePostJoinManual(sfWebRequest $request)
  {
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $this->retUrl = $this->retUrlRaw;

    $this->teamList = $this->game->getTeamsAvailableToPostJoinBy($this->sessionWebUser);
    $this->errorRedirectIf($this->teamList->count() <= 0, 'Нет команд, от лица которых Вы можете подать заявку на игру.');
  }

  public function executeAddTeamManual(sfWebRequest $request)
  {
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $this->errorRedirectUnless($this->game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, ' регистрировать команды на игру'));
    $this->retUrl = $this->retUrlRaw;

    $this->teamList = new Doctrine_Collection('Team');
    foreach (Team::all() as $team)
    {
      if (   (!$this->game->isTeamRegistered($team))
          && (!$this->game->isTeamCandidate($team))
          && ($team->id != $this->game->team_id))
      {
        $this->teamList->add($team);
      }
    }
    $this->errorRedirectIf($this->teamList->count() <= 0, 'Нет команд, которые вы можете зарегистрировать.');
  }

  public function executePostJoin(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->postJoin($this->team, $this->sessionWebUser)))
    {
      $this->errorRedirect('Не удалось подать заявку команды '.$this->team->name.' на игру '.$this->game->name.': '.$res);
    }
    else
    {
      if ($this->game->team_id > 0)
      {
        Utils::sendNotifyGroup(
            'Заявка '.$this->team->name.' на игру '.$this->game->name,
            'Команда "'.$this->team->name.'" подала заявку на вашу игру "'.$this->game->name.'".'."\n"
            .'Утвердить или отклонить: http://'.SystemSettings::getInstance()->site_domain.'/game/show?id='.$this->game->id.'&tab=teams',
            $this->game->Team->getLeadersRaw()
        );
      }
      else
      {
        Utils::sendNotifyAdmin(
            'Заявка '.$this->team->name.' на игру '.$this->game->name,
            'Команда "'.$this->team->name.'" подала заявку на игру "'.$this->game->name.'", которой не назначена команда-организатор.'."\n"
            .'Утвердить или отклонить: http://'.SystemSettings::getInstance()->site_domain.'/game/show?id='.$this->game->id.'&tab=teams'
        );
      }
      $this->successRedirect('Заявка команды '.$this->team->name.' на игру '.$this->game->name.' принята.');
    }
  }

  public function executeCancelJoin(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->cancelJoin($this->team, $this->sessionWebUser)))
    {
      $this->errorRedirect('Не удалось отменить заявку команды '.$this->team->name.' на игру '.$this->game->name.': '.$res);
    }
    else
    {
      Utils::sendNotifyGroup(
          'Отклонена регистрация '.$this->team->name.' на игру '.$this->game->name,
          'Заявка вашей команды "'.$this->team->name.'" на участие в игре "'.$this->game->name.'" отклонена.',
          $this->team->getLeadersRaw()
      );
      
      $this->successRedirect('Отменена заявка команды '.$this->team->name.' на игру '.$this->game->name.'.');
    }
  }

  public function executeAddTeam(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->registerTeam($this->team, $this->sessionWebUser)))
    {
      $this->errorRedirect('Не удалось зарегистрировать команду '.$this->team->name.' на игру '.$this->game->name.': '.$res);
    }
    else
    {
      Utils::sendNotifyGroup(
          'Регистрация '.$this->team->name.' на игру '.$this->game->name,
          'Ваша команда "'.$this->team->name.'" принята к участию в игре "'.$this->game->name.'"'."\n"
          .'Афиша игры: http://'.SystemSettings::getInstance()->site_domain.'/game/info?id='.$this->game->id,
          $this->team->getLeadersRaw()
      );      
    
      $this->successRedirect('Команда '.$this->team->name.' зарегистрирована на игру '.$this->game->name.'.');
    }
  }

  public function executeRemoveTeam(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->unregisterTeam($this->team, $this->sessionWebUser)))
    {
      $this->errorRedirect('Не удалось снять команду '.$this->team->name.' с игры '.$this->game->name.': '.$res);
    }
    else
    {
      Utils::sendNotifyGroup(
          'Команда '.$this->team->name.' снята с игры '.$this->game->name,
          'Ваша команда "'.$this->team->name.'" снята с игры "'.$this->game->name.'".',
          $this->team->getLeadersRaw()
      );
      
      $this->successRedirect('Команда '.$this->team->name.' снята с игры '.$this->game->name.'.');
    }
  }

  /**
   * Раскодирует для не-"...manual" процедур web-параметры и пишет их в локальные свойства.
   * Используются следующие параметры:
   * - id     - ключ игры
   * - teamId - ключ команды
   *
   * @param   sfWebRequest    $request  исходный запрос
   */
  protected function decodeArgs(sfWebRequest $request)
  {
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
    $this->forward404Unless($this->team = Team::byId($request->getParameter('teamId')), 'Команда не найдена.');
  }

}
