<?php

/**
 * TaskConstraint actions.
 *
 * @package    sf
 * @subpackage TaskConstraint
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TaskConstraintActions extends MyActions
{
  
  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($task = Task::byId($request->getParameter('taskId')), 'Задание не найдено');
    $this->errorRedirectUnless($task->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать правила перехода'));
    $newTaskConstraint = new TaskConstraint;
    $newTaskConstraint->task_id = $task->id;
    $this->form = new TaskConstraintForm($newTaskConstraint);
    $this->_task = $task;
    $this->_game = $task->Game;
    $this->form->buildQuery($task->id);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new TaskConstraintForm();
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
    $this->forward404Unless($taskConstraint = TaskConstraint::byId($request->getParameter('id')), 'Правило перехода не найдено.');
    $this->errorRedirectUnless($taskConstraint->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять правила перехода'));
    $this->form = new TaskConstraintForm($taskConstraint);
    $this->form->buildQuery($taskConstraint->task_id);

    $this->_task = Task::byId($taskConstraint->task_id);
    $this->_game = $this->_task->Game;
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($taskConstraint = TaskConstraint::byId($request->getParameter('id')), 'Правило перехода не найдено.');
    $this->form = new TaskConstraintForm($taskConstraint);
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
    $this->forward404Unless($taskConstraint = TaskConstraint::byId($request->getParameter('id')), 'Правило перехода не найдено.');
    $this->errorRedirectUnless($taskConstraint->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять правила перехода'));
    $taskConstraint->delete();
    $this->successRedirect('Правило перехода успешно удалено.');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять правило перехода'));
      
      if ($object->priority_shift != 0)
      {
        $srcTask = Task::byId($object->task_id);
        $targetTask = Task::byId($object->target_task_id);
        if ($targetTask !== false)
        {
          if ($targetTask->id != $srcTask->id)
          {
            $object->save();
            $this->successRedirect('Правило перехода c задания '.$srcTask->name.' на задание '.$targetTask->name.' успешно сохранено.', 'task/show?id='.$srcTask->id);
          }
          else
          {
            $this->errorMessage('Сохранить правило перехода не удалось. Переход задания самого на себя не имеет смысла.');
          }
        }
        else
        {
          $this->errorMessage('Сохранить правило перехода не удалось. Не найдено указанное целевое задание.');
        }
      }
      else
      {
        $this->errorMessage('Сохранить правило перехода не удалось. Приоритет должен быть отличен от нуля.');
      }
    }
    else
    {
      $this->errorMessage('Сохранить правило перехода не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }
}
