<?php

/**
 * TaskTransition actions.
 *
 * @package    sf
 * @subpackage TaskTransition
 * @author     VozdvIN
 */
class TaskTransitionActions extends MyActions
{
  
  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($task = Task::byId($request->getParameter('taskId')), 'Задание не найдено');
    $this->errorRedirectUnless($task->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать фильтры перехода'));
    $newTaskTransition = new TaskTransition;
    $newTaskTransition->task_id = $task->id;
    $this->form = new TaskTransitionForm($newTaskTransition);
    $this->_task = $task;
    $this->_game = $task->Game;
    $this->form->buildQuery($task->id);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new TaskTransitionForm();
    $this->form->buildQuery($request->getParameter('taskId'));
    $this->processForm($request, $this->form);

    $formData = $this->form->getTaintedValues();
    $this->form->buildQuery($formData['task_id']);

    $this->_task = Task::byId($formData['task_id']);
    $this->_game = $this->_task->Game;
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($taskTransition = TaskTransition::byId($request->getParameter('id')), 'Фильтр перехода не найден.');
    $this->errorRedirectUnless($taskTransition->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять фильтры перехода'));
    $this->form = new TaskTransitionForm($taskTransition);
    $this->form->buildQuery($taskTransition->task_id);

    $this->_task = Task::byId($taskTransition->task_id);
    $this->_game = $this->_task->Game;
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($taskTransition = TaskTransition::byId($request->getParameter('id')), 'Фильтр перехода не найден.');
    $this->form = new TaskTransitionForm($taskTransition);
    $this->processForm($request, $this->form);

    $formData = $this->form->getTaintedValues();
    $this->form->buildQuery($formData['task_id']);

    $this->_task = Task::byId($formData['task_id']);
    $this->_game = $this->_task->Game;
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($taskTransition = TaskTransition::byId($request->getParameter('id')), 'Фильтр перехода не найден.');
    $this->errorRedirectUnless($taskTransition->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять фильтры перехода'));
    $taskTransition->delete();
    $this->successRedirect('Фильтр перехода успешно удален.');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять фильтр перехода'));
      
      if ($object->allow_on_success || $object->allow_on_fail)
      {
        $srcTask = Task::byId($object->task_id);
        $targetTask = Task::byId($object->target_task_id);
        if ($targetTask !== false)
        {
          if ($targetTask->id != $srcTask->id)
          {
            $object->save();
            $this->successRedirect('Фильтр перехода c задания '.$srcTask->name.' на задание '.$targetTask->name.' успешно сохранен.', 'task/show?id='.$srcTask->id);
          }
          else
          {
            $this->errorMessage('Сохранить фильтр перехода не удалось. Переход задания самого на себя не имеет смысла.');
          }
        }
        else
        {
          $this->errorMessage('Сохранить фильтр перехода не удалось. Не найдено указанное целевое задание.');
        }
      }
      else
      {
        $this->errorMessage('Сохранить фильтр перехода не удалось. Должен быть проставлен хотя бы один из признаков "При успехе" или "При неудаче".');
      }
    }
    else
    {
      $this->errorMessage('Сохранить фильтр перехода не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }
}
