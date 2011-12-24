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
    
    $teamName = $teamCreateRequest->name;
    $webUser = $teamCreateRequest->WebUser;
    $teamCreateRequest->delete();
    Utils::sendNotifyUser(
        'Заявка отклонена - '.$teamName,
        'Ваша заявка на создание команды "'.$teamName.'" отклонена.',
        $webUser
    );    
    
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
        $object->tag = Utils::generateActivationKey();
        $object = $form->save();
        
        $settings = SystemSettings::getInstance();
        if ($settings->email_team_create && !$settings->fast_team_create)
        {
          $notifyResult = Utils::sendNotify(
              'Создание команды '.$object->name,
              'Вы запросили создание команды "'.$object->name.'".'."\n"
              .'Для подтверждения создания команды перейдите по ссылке:'."\n"
              .'http://'.$settings->site_domain.'/auth/createTeam?id='.$object->id.'&key='.$object->tag."\n"
              .'Отменить заявку можно здесь: http://'.$settings->site_domain.'/team/index',
              $object->WebUser->email
          );        
          
          if ($notifyResult)
          {
            $this->newTeamCreateRequestNotify($object);
            $this->successRedirect('Заявка на создание команды '.$object->name.' принята. Вам отправлено письмо для ее подтверждения.', 'team/index');
          }
          else
          {
            // Тут посылать письмо админам смысла нет...
            $this->warningRedirect('Заявка на создание команды '.$object->name.' принята, но не удалось отправить письмо для ее подтверждения. Обратитесь к администрации сайта.', 'team/index');
          }        
        }
        else
        {
          $this->newTeamCreateRequestNotify($object);
          $this->successRedirect('Заявка на создание команды '.$object->name.' принята. Ожидайте, пока она пройдет модерацию.', 'team/index');
        }        
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
    
    $fastTeamCreate = SystemSettings::getInstance()->fast_team_create;
    if ($fastTeamCreate)
    {
      $this->errorRedirectUnless(
          $this->sessionWebUser->id == $teamCreateRequest->web_user_id
          || $this->sessionWebUser->can(Permission::TEAM_MODER, 0),
          'В режиме быстрого создания команд создать команду может только автор заявки.',
          'team/index'
      );
    }
    else
    {
      $this->errorRedirectUnless(
          $this->sessionWebUser->can(Permission::TEAM_MODER, 0),
          'Создать команду по заявке может только модератор команд.',
          'team/index'
    );
    }

    $this->errorRedirectUnless(
        Utils::byField('Team', 'name', $teamCreateRequest->name) === false,
        'Не удалось создать команду: команда '.$teamCreateRequest->name.' уже существует.',
        'team/index'
    );
    
    $webUser = $teamCreateRequest->WebUser;
    $team = TeamCreateRequest::doCreate($teamCreateRequest);
    Utils::sendNotifyUser(
        'Команда создана - '.$team->name,
        'Ваша заявка на создание команды "'.$team->name.'" утверждена, команда создана.'."\n"
        .'Страница команды: http://'.SystemSettings::getInstance()->site_domain.'/team/show?id='.$team->id,
        $webUser
    );
    
    $this->successRedirect(
        'Команда '.$team->name.' успешно создана.',
        $fastTeamCreate ? 'team/index' : 'team/show?id='.$team->id
    );
  }
  
  protected function newTeamCreateRequestNotify(TeamCreateRequest $teamCreateRequest)
  {
    Utils::sendNotifyAdmin(
        'Новая команда - '.$teamCreateRequest->name,
        'Подана заявка на создание команды:'."\n"
        .'- название: '.$teamCreateRequest->name."\n"
        .'- автор заявки: '.$teamCreateRequest->WebUser->login.(($teamCreateRequest->WebUser->email !== '') ? ' ('.$teamCreateRequest->WebUser->email.')' : '')."\n"
        .'- сообщение: '.$teamCreateRequest->description."\n"
        .'Утвердить или отклонить: http://'.SystemSettings::getInstance()->site_domain.'/team/index'
    );      
  }
}
?>