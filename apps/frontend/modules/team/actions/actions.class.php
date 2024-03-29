<?php

/**
 * team actions.
 *
 * @package    sf
 * @subpackage team
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class teamActions extends MyActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $this->errorRedirectIf($this->sessionWebUser->cannot(Permission::TEAM_INDEX, 0), Utils::cannotMessage($this->sessionWebUser->login, 'просматривать список команд'));

    $this->_currentRegion = Region::byIdSafe($this->session->getAttribute('region_id'));
    
    if ($this->_currentRegion->id == Region::DEFAULT_REGION)
    {
      $this->_teams = Doctrine::getTable('Team')
          ->createQuery('t')->leftJoin('t.teamPlayers')->leftJoin('t.teamCandidates')
          ->select()->orderBy('name')
          ->execute();
    }
    else
    {
      $this->_teams = Doctrine::getTable('Team')
          ->createQuery('t')->leftJoin('t.teamPlayers')->leftJoin('t.teamCandidates')
          ->select()
          ->where('region_id = ?', $this->_currentRegion->id)
          ->orderBy('name')
          ->execute();      
    }  
    
    $this->_isModerator = $this->sessionWebUser->can(Permission::TEAM_MODER, 0);
    $this->_fastTeamCreate = SystemSettings::getInstance()->fast_team_create;
    if ($this->_isModerator)
    {
      $this->_teamCreateRequests = Doctrine::getTable('TeamCreateRequest')
          ->createQuery('tcr')
          ->select()
          ->orderBy('name')
          ->execute();
    }
    else
    {
      $this->_teamCreateRequests = Doctrine::getTable('TeamCreateRequest')
          ->createQuery('tcr')
          ->select()
          ->where('web_user_id = ?', $this->sessionWebUser->id)
          ->orderBy('name')
          ->execute();
    }
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->_team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    //Просматривать команду могут все,
    //но конкретный перечень видимых элементов зависит от прав.
    //Детализируем права:
    $this->_sessionWebUserId = $this->sessionWebUser->id;
    $this->_sessionIsModerator = $this->sessionWebUser->can(Permission::TEAM_MODER, $this->_sessionWebUserId);
    $this->_sessionIsLeader    = $this->_team->isLeader($this->sessionWebUser);
    $this->_sessionIsPlayer    = $this->_team->isPlayer($this->sessionWebUser);
    $this->_sessionIsCandidate = $this->_team->isCandidate($this->sessionWebUser);
    if ($this->_sessionIsPlayer || $this->_sessionIsModerator)
    {
      //Капитану и игрокам видны все заявки в команду
      $this->_teamCandidates = $this->_team->teamCandidates;
    }
    else
    {
      //Кандидату в команду видна только своя заявка
      $this->_teamCandidates = Doctrine::getTable('TeamCandidate')
          ->createQuery('tc')
          ->select()
          ->where('team_id = ?', $this->_team->id)
          ->andWhere('web_user_id = ?', $this->_sessionWebUserId)
          ->execute();
    }
    $this->_teamStates = $this->_team->teamStates;
    $this->_games = $this->_team->games;
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->errorRedirectUnless(Team::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать команду'));
    $this->form = new TeamForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->errorRedirectUnless(Team::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать команду'));
    $this->form = new TeamForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    $this->errorRedirectUnless($this->team->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'редактировать команду'));
    $this->form = new TeamForm($this->team);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($this->team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    $this->form = new TeamForm($this->team);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($this->team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    $this->errorRedirectUnless(Team::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять команду'));
    $this->team->delete();
    $this->successRedirect('Команда успешно удалена', 'team/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять команду'));
      $object->save();

      $webUser = WebUser::byId($form->getValue('leader', 0));
      if (!$webUser)
      {
        $this->successRedirect('Команда '.$object->name.' успешно сохранена.', 'team/show?id='.$object->id);
      }
      else
      {
        $object->registerPlayer($webUser, true, $this->sessionWebUser);
        $this->successRedirect('Команда '.$object->name.' успешно сохранена, ее капитаном назначен '.$webUser->login.'.', 'team/show?id='.$object->id);
      }
    }
    else
    {
      $this->errorMessage('Сохранить команду не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

  public function executePostJoin(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    if (is_string($res = $this->team->postJoin($this->candidate, $this->sessionWebUser)))
    {
      $this->errorRedirect('Зарегистрировать заявку в состав команды '.$this->team->name.' не удалось: '.$res);
    }
    
    Utils::sendNotifyGroup(
        'Заявка в '.$this->team->name.' от '.$this->candidate->login,
        'В состав вашей команды "'.$this->team->name.'" попросился '.$this->candidate->login.".\n"
        .'Принять или отклонить: http://'.SystemSettings::getInstance()->site_domain.'/team/show?id='.$this->team->id,
        $this->team->getLeadersRaw()
    );
    
    $this->successRedirect($this->candidate->login.' подал заявку в состав команды '.$this->team->name.'.');
  }

  public function executeCancelJoin(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    $res = $this->team->cancelJoin($this->candidate, $this->sessionWebUser);
    if (is_string($res))
    {
      $this->errorRedirect('Отменить заявку в состав команды '.$this->team->name.' не удалось: '.$res);
    }

    Utils::sendNotifyUser(
        'Отклонена заявка в команду '.$this->team->name,
        'Ваша заявка в состав команды '.$this->team->name.' отклонена.',
        $this->candidate
    );
    
    $this->successRedirect('Заявка от '.$this->candidate->login.' в состав команды '.$this->team->name.' отменена.');
  }

  public function executeSetPlayer(sfWebRequest $request)
  {
    $this->prepareArgs($request);

    //Если команда пуста, то игрок станет капитаном, это надо запомнить.
    $willBeLeader = $this->team->teamPlayers->count() == 0;
    
    if (is_string($res = $this->team->registerPlayer($this->candidate, ($this->team->teamPlayers->count() == 0), $this->sessionWebUser)))
    {
      $this->errorRedirect('Назначить '.$this->candidate->login.' игроком команды '.$this->team->name.' не удалось: '.$res);
    }
    
    Utils::sendNotifyUser(
        ($willBeLeader
        ? ('Вы капитан '.$this->team->name)
        : ('Вы рядовой в '.$this->team->name)),
        ($willBeLeader
        ? ('Вы назначены капитаном команды "'.$this->team->name."\".\n")
        : ('Вы приняты рядовым в команду "'.$this->team->name."\".\n"))
        .'Страница команды: http://'.SystemSettings::getInstance()->site_domain.'/team/show?id='.$this->team->id,
        $this->candidate
    );
    
    $this->successRedirect(
        $willBeLeader
            ? $this->candidate->login.' назначен капитаном команды '.$this->team->name.'.'
            : $this->candidate->login.' принят рядовым в команду '.$this->team->name.'.'
    );
  }

  public function executeSetLeader(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    if (is_string($res = $this->team->registerPlayer($this->candidate, true, $this->sessionWebUser)))
    {
      $this->errorRedirect('Назначить '.$this->candidate->login.' капитаном команды '.$this->team->name.' не удалось: '.$res);
    }

    Utils::sendNotifyUser(
        'Вы в команде '.$this->team->name,
        'Вы назначены капитаном команды "'.$this->team->name."\"\n"
        .'Страница команды: http://'.SystemSettings::getInstance()->site_domain.'/team/show?id='.$this->team->id,
        $this->candidate
    );
    
    $this->successRedirect($this->candidate->login.' назначен капитаном команды '.$this->team->name.'.');
  }

  public function executeUnregister(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    if (is_string($res = $this->team->unregisterPlayer($this->candidate, $this->sessionWebUser)))
    {
      $this->errorRedirect('Исключить '.$this->candidate->login.' из состава команды '.$this->team->name.' не удалось: '.$res);
    }

    Utils::sendNotifyUser(
        'Исключение из команды '.$this->team->name,
        'Вы исключены из состава команды '.$this->team->name,
        $this->candidate
    );
 
    $this->successRedirect($this->candidate->login.' исключен из состава команды '.$this->team->name.'.');
  }

  public function executeRegisterPlayer(sfWebRequest $request)
  {
    $this->forward404Unless($team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    if (!$team->canBeManaged($this->sessionWebUser))
    {
      $this->errorRedirect(Utils::cannotMessage($this->sessionWebUser->login, 'регистрировать игрока в команду '.$team->name));
    }
    $this->webUsers = new Doctrine_Collection('WebUser');
    foreach (Doctrine::getTable('WebUser')->createQuery('wu')->orderBy('wu.login')->execute() as $webUser)
    {
      if ((!$team->isPlayer($webUser)) && (!$team->isCandidate($webUser)))
      {
        $this->webUsers->add($webUser);        
      }
    }
    $this->team = $team;
    $this->retUrl = $this->retUrlRaw;
  }

  /**
   * Поготавливает переменные путем выбора параметров запроса.
   * Входные аргументы из $request:
   * - id - ключ команды, над которой выполняется операция
   * - userId - ключ пользователя, над которым выполняется операция.
   *
   * @param   sfWebRequest  $request
   */
  protected function prepareArgs(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->forward404Unless($this->team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    $this->forward404Unless($this->candidate = WebUser::byId($request->getParameter('userId')), 'Пользователь не найден.');
  }

}
