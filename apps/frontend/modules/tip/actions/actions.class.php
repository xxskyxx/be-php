<?php

/**
 * Tip actions.
 *
 * @package    sf
 * @subpackage tip
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tipActions extends MyActions
{

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($task = Task::byId($request->getParameter('taskId')), 'Задание не найдено');
    $this->errorRedirectUnless($task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать подсказку к заданию'));
    $newTip = new Tip;
    $newTip->task_id = $task->id;
    if ($task->tips->count() == 0)
    {
      $newTip->name = $task->Game->task_define_default_name;
      $newTip->define = 'Формулировка_задания_'.$task->name;
    }
    else
    {
      $newTip->name = $task->Game->task_tip_prefix.$task->tips->count();
      $newTip->define = 'Подсказка'.$task->tips->count().'_к_заданию_'.$task->name;
    }    
    $this->form = new TipForm($newTip);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new TipForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($tip = Tip::byId($request->getParameter('id')), 'Подсказка не найдена');
    $this->errorRedirectUnless($tip->Task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять подсказку к заданию'));
    $this->form = new TipForm($tip);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($tip = Tip::byId($request->getParameter('id')), 'Подсказка не найдена');
    $this->form = new TipForm($tip);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($tip = Tip::byId($request->getParameter('id')), 'Подсказка не найдена');
    $this->errorRedirectUnless($tip->Task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять подсказку к заданию'));
    $tip->delete();
    $this->successRedirect('Подсказка к заданию '.$task->name.' удалена.');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->Task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять подсказку к заданию'));
      if ($object->isNew())
      {
        $object->initDefaults();
      }
      $object->save();
      $this->successRedirect('Подсказка '.$object->name.' к заданию '.$object->Task->name.' успешно сохранена.', 'task/show?id='.$object->task_id);
    }
    else
    {
      $this->errorMessage('Сохранить подсказку не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

}
