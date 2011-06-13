<?php

/**
 * task actions.
 *
 * @package    sf
 * @subpackage task
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class taskActions extends MyActions
{

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->task = Task::byId($request->getParameter('id')), 'Задание не найдено');
    $this->errorRedirectUnless($this->task->Game->canBeObserved($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'просматривать задание'));
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($game = Game::byId($request->getParameter('gameId')), 'Игра не найдена');
    $this->errorRedirectUnless($game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать задания для игры'));
    $newTask = new Task;
    $newTask->game_id = $game->id;
    $newTask->name = 'Задание'.($game->tasks->count()+1);
    $this->form = new taskForm($newTask);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new taskForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($task = Task::byId($request->getParameter('id')), 'Задание не найдено.');
    $this->errorRedirectUnless($task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять задания для игры'));
    $this->form = new taskForm($task);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($task = Task::byId($request->getParameter('id')), 'Задание не найдено.');
    $this->form = new taskForm($task);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($this->task = Task::byId($request->getParameter('id')), 'Задание не найдено.');
    $this->errorRedirectUnless($this->task->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять задания для игры'));
    $game_id = $this->task->game_id;
    $this->task->delete();
    $this->successRedirect('Задание успешно удалено.', 'game/show?id='.$game_id);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $wasNew = $object->isNew();
      $this->errorRedirectUnless($object->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять задания для игры'));
      $object->initDefaults();
      $object->save();
      if ($wasNew)
      {
        // Задание новое, надо редактировать его формулировку.
        $tip = $object->tips->getFirst();
        $this->successRedirect('Задание '.$object->name.' игры '.$object->Game->name.' успешно сохранено. Отредактируйте его формулировку.', 'tip/edit?id='.$tip->id);
      }
      else
      {
        // Задание не новое, редактировать его формулировку не надо.
        $this->successRedirect('Задание '.$object->name.' игры '.$object->Game->name.' успешно сохранено.', 'task/show?id='.$object->id);
      }
    }
    else
    {
      $this->errorMessage('Сохранить ответ не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

}
