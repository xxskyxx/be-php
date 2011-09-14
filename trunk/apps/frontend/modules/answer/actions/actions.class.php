<?php

/**
 * answer actions.
 *
 * @package    sf
 * @subpackage answer
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class answerActions extends MyActions
{

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($task = Task::byId($request->getParameter('taskId')), 'Задание не найдено');
    $this->errorRedirectUnless($task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'создавать ответ к заданию'));
    $newAnswer = new Answer;
    $newAnswer->task_id = $task->id;
    $newAnswer->name = 'Ответ'.($task->answers->count()+1);
    $this->form = new AnswerForm($newAnswer);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new AnswerForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($answer = Answer::byId($request->getParameter('id')), 'Ответ не найден.');
    $this->errorRedirectUnless($answer->Task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять ответ к заданию'));
    $this->form = new AnswerForm($answer);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($answer = Answer::byId($request->getParameter('id')), 'Ответ не найден.');
    $this->form = new AnswerForm($answer);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($answer = Answer::byId($request->getParameter('id')), 'Ответ не найден.');
    $this->errorRedirectUnless($answer->Task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять ответ к заданию'));
    $answer->delete();
    $this->successRedirect('Ответ к заданию удален.');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->Task->Game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять ответ к заданию'));
      $object->save();
      $this->successRedirect('Ответ '.$object->name.' к заданию '.$object->Task->name.' успешно сохранен.', 'task/show?id='.$object->task_id);
    }
    else
    {
      $this->errorMessage('Сохранить ответ не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

}
