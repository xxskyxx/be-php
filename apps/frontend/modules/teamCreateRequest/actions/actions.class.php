<?php

/**
 * teamCreateRequest actions.
 *
 * @package    sf
 * @subpackage teamCreateRequest
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class teamCreateRequestActions extends MyActions
{

  public function preExecute()
  {
    parent::preExecute();
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $teamCreateRequest = new TeamCreateRequest();
    $teamCreateRequest->web_user_id = $this->sessionWebUser;
    $this->form = new TeamCreateRequestForm($teamCreateRequest);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new TeamCreateRequestForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->errorRedirectUnless(
        $teamCreateRequest = TeamCreateRequest::byId($request->getParameter('id')),
        'Заявка на создание команды не найдена'
    );
    $this->errorRedirectUnless(
        $this->sessionWebUser->id == $teamCreateRequest->web_user_id
        || $this->sessionWebUser->can(Permission::TEAM_MODER, 0),
        'Отменить заявку на создание команды может только ее автор или модератор команд.'
    );
    
    $teamCreateRequest->delete();
    $this->successRedirect('Заявка на создание команды успешно отменена.', 'team/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      if ((Utils::byField('Team', 'name', $object->name) === false)
          && (Utils::byField('TeamCreateRequest', 'name', $object->name) === false))
      {
        $object = $form->save();
        $this->successRedirect('Заявка на создание команды '.$object->name.' принята.', 'team/index');
      }
      else
      {
        $this->errorMessage('Не удалось подять заявку: команда или заявка с таким названием уже существует.');
      }
    }
  }
  
  public function executeAcceptManual(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->errorRedirectUnless(
        $teamCreateRequest = TeamCreateRequest::byId($request->getParameter('id')),
        'Заявка на создание команды не найдена',
        'team/index'
    );
    $this->errorRedirectUnless(
        $this->sessionWebUser->can(Permission::TEAM_MODER, 0),
        'Создать команду по заявке может только модератор команд.',
        'team/index'
    );
    $this->errorRedirectUnless(
        Utils::byField('Team', 'name', $teamCreateRequest->name) === false,
        'Не удалось создать команду: команда '.$teamCreateRequest->name.' уже существует.',
        'team/index'
    );
    
    $team = new Team;
    $team->name = $teamCreateRequest->name;
    $team->full_name = $teamCreateRequest->full_name;
    $team->save(); //Требуется, так как иначе не удастся включить капитана.
    $team->registerPlayer($teamCreateRequest->WebUser, true, $this->sessionWebUser);
    $team->save();
    $teamCreateRequest->delete();
    
    $this->successRedirect('Команда '.$team->name.' успешно создана.', 'team/index');
  }
}
