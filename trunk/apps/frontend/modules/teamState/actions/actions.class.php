<?php

/**
 * teamState actions.
 *
 * @package    sf
 * @subpackage teamState
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class teamStateActions extends myActions
{

  public function preExecute()
  {
    parent::preExecute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->_teamState = TeamState::byId($request->getParameter('id')), 'Состояние команды не найдено.');
    $this->errorRedirectUnless($this->_teamState->canBeObserved($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'просматривать настройки команды'));
    $this->_sessionCanManage = $this->_teamState->canBeManaged($this->sessionWebUser);
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($teamState = TeamState::byId($request->getParameter('id')), 'Состояние команды не найдено.');
    $this->errorRedirectUnless($teamState->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять настройки команды'));
    $this->form = new teamStateForm($teamState);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($teamState = TeamState::byId($request->getParameter('id')), 'Состояние команды не найдено.');
    $this->form = new teamStateForm($teamState);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять настройки команды'));
      $object->save();
      $this->successRedirect('Настройки команды '.$object->Team->name.' успешно сохранены.', 'game/show?id='.$object->game_id.'&tab=teams');
    }
    else
    {
      $this->errorMessage('Сохранить настройки команды не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

  public function executeTask(sfWebRequest $request)
  {
    $this->forward404Unless($this->_teamState = TeamState::byId($request->getParameter('id')), 'Состояние команды не найдено.');
    $this->errorRedirectUnless($this->_teamState->canBeObserved($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'просматривать текущее задание команды'));

    if ($this->_teamState->Game->teams_can_update)
    {
      if (is_string($res = $this->_teamState->updateState($this->sessionWebUser)))
      {
        $this->errorMessage('Не удалось обновить состояние команды: '.$res);
      }
      else
      {
        $this->_teamState->save();
      }
    }

    if ($currentTaskStatus = $this->_teamState->getCurrentTaskState())
    {
      if ($currentTaskStatus->status < TaskState::TASK_DONE)
      {
        $this->redirectSafe('taskState/task?id='.$currentTaskStatus->id);
      }
    }
    
    switch ($this->_teamState->status)
    {
      case TeamState::TEAM_WAIT_GAME: $this->setTemplate('teamWaitGame'); break;
      case TeamState::TEAM_WAIT_START: $this->setTemplate('teamWaitStart'); break;
      case TeamState::TEAM_WAIT_TASK:
        $this->_availableTasksManual = $this->_teamState->getTasksAvailableForManualSelect();
        if ($this->_availableTasksManual && ($this->_teamState->task_id <= 0))
        {
          $this->_isLeader = $this->_teamState->Team->canBeManaged($this->sessionWebUser);
          $this->setTemplate('teamSelectTask');
        }
        else
        {
          $this->setTemplate('teamWaitTask');
        }
        break;
      case TeamState::TEAM_HAS_TASK: $this->setTemplate('teamHasTask'); break;
      case TeamState::TEAM_FINISHED: $this->setTemplate('teamFinished'); break;
        
      default:
        throw new Exception('TeamStateActions::executeTask: Неизвестное состояние команды - #'.$this->_teamState->status);
    }
  }

  public function executeAbandonTask(sfWebRequest $request)
  {
    $this->forward404Unless($teamState = TeamState::byId($request->getParameter('id')), 'Состояние команды не найдено.');
    $this->errorRedirectUnless($teamState->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'управлять состоянием команды'));
    if (is_string($res = $teamState->abandonTask($this->sessionWebUser)))
    {
      $this->errorRedirect('Не удалось отменить текущее задание команды '.$teamState->Team->name.' : '.$res);
    }
    $teamState->save();
    $this->successRedirect('Текущее задание команды '.$teamState->Team->name.' успешно отменено.');

  }
}
