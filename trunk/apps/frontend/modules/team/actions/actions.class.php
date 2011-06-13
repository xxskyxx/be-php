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
    $this->errorRedirectIf($this->sessionWebUser->cannot(Permission::TEAM_INDEX, 0),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать список команд'));
    $this->teams = Team::all();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->team = Team::byId($request->getParameter('id')), 'Команда не найдена.');
    $this->errorRedirectUnless($this->team->canBeObserved($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'просматривать команду'));
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
    $this->successRedirect('Пользователь '.$this->candidate->login.' подал заявку в состав команды '.$this->team->name.'.');
  }

  public function executeCancelJoin(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    $res = $this->team->cancelJoin($this->candidate, $this->sessionWebUser);
    if (is_string($res))
    {
      $this->errorRedirect('Отменить заявку в состав команды '.$this->team->name.' не удалось: '.$res);
    }
    $this->successRedirect('Заявка пользователя '.$this->candidate->login.' в состав команды '.$this->team->name.' отменена.');
  }

  public function executeSetPlayer(sfWebRequest $request)
  {
    $this->prepareArgs($request);

    //Если команда пуста, то игрок станет капитаном, это надо запомнить.
    $willBeLeader = $this->team->teamPlayers->count() == 0;
    
    if (is_string($res = $this->team->registerPlayer($this->candidate, ($this->team->teamPlayers->count() == 0), $this->sessionWebUser)))
    {
      $this->errorRedirect('Назначить пользователя рядовым игроком команды '.$this->team->name.' не удалось: '.$res);
    }
    
    $this->successRedirect(
        $willBeLeader
            ? 'Пользователь '.$this->candidate->login.' назначен капитаном команды '.$this->team->name.'.'
            : 'Пользователь '.$this->candidate->login.' назначен рядовым в команде '.$this->team->name.'.'
    );
  }

  public function executeSetLeader(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    if (is_string($res = $this->team->registerPlayer($this->candidate, true, $this->sessionWebUser)))
    {
      $this->errorRedirect('Назначить пользователя капитаном команды '.$this->team->name.' не удалось: '.$res);
    }
    $this->successRedirect('Пользователь '.$this->candidate->login.' назначен капитаном команды '.$this->team->name.'.');
  }

  public function executeUnregister(sfWebRequest $request)
  {
    $this->prepareArgs($request);
    if (is_string($res = $this->team->unregisterPlayer($this->candidate, $this->sessionWebUser)))
    {
      $this->errorRedirect('Исключить игрока из состава команды '.$this->team->name.' не удалось: '.$res);
    }
    $this->successRedirect('Пользователь '.$this->candidate->login.' исключен из состава команды '.$this->team->name.'.');
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
