<?php
/**
 * gameCreateRequest actions.
 *
 * @package    sf
 * @subpackage gameCreateRequest
 * @author     VozdvIN
 */

class gameCreateRequestActions extends MyActions
{

  public function preExecute()
  {
    parent::preExecute();
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $this->errorRedirectUnless(
        $team = Team::byId($request->getParameter('teamId')),
        'Не указана команда, которая организует игру'
    );
    $gameCreateRequest = new gameCreateRequest();
    $gameCreateRequest->team_id = $team->id;
    $this->form = new GameCreateRequestForm($gameCreateRequest);
  }
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new GameCreateRequestForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->errorRedirectUnless(
        $gameCreateRequest = GameCreateRequest::byId($request->getParameter('id')),
        'Заявка на создание игры не найдена'
    );
    $this->errorRedirectUnless(
        $gameCreateRequest->Team->canBeManaged($this->sessionWebUser)
        || $this->sessionWebUser->can(Permission::GAME_MODER, 0),
        'Отменить заявку на создание игры может только капитан команды организаторов или модератор игр.'
    );
    
    $gameCreateRequest->delete();
    $this->successRedirect('Заявка на создание игры успешно отменена.', 'game/index');
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      if ((Utils::byField('Game', 'name', $object->name) === false)
          && (Utils::byField('GameCreateRequest', 'name', $object->name) === false))
      {
        $object->tag = Utils::generateActivationkey();
        $object = $form->save();
        
        $settings = SystemSettings::getInstance();
        if ($settings->email_game_create)
        {
          $message = Swift_Message::newInstance('Создание игры '.$object->name.' ('.$settings->site_name.')')
              ->setFrom(array($settings->notify_email_addr => $settings->site_name))
              ->setTo($this->sessionWebUser->email)
              ->setBody(
                   "Здравствуйте!\n\n"
                  ."Вы получили это письмо, так как запросили создание игры \"".$object->name."\" на сайте ".$settings->site_name.".\n"
                  ."Если Вы не создавали игру, просто проигнорируйте это письмо.\n\n"
                  ."Для подтверждения создания игры перейдите по указанной ссылке:\n"
                  ."http://".$settings->site_domain."/auth/createGame?id=".$object->id."&key=".$object->tag."\n\n"
                  ."Отменить заявку можно на странице игр:\nhttp://".$settings->site_domain."/game/index\n\n"
                  ."Не отвечайте на это письмо! Оно было отправлено почтовым роботом.\n"
                  ."Для связи с администрацией сайта используйте адрес ".$settings->contact_email_addr
          );          
          
          if (Utils::sendEmailSafe($message, Utils::getReadyMailer()))
          {
            $this->successRedirect('Заявка на создание игры '.$object->name.' принята. Вам отправлено письмо для ее подтверждения.', 'game/index');
          }
          else
          {
            $this->warningRedirect('Заявка на создание игры '.$object->name.' принята, но не удалось отправить письмо для ее подтверждения. Обратитесь к администрации сайта.', 'game/index');
          }        
        }
        else
        {
          $this->successRedirect('Заявка на создание игры '.$object->name.' принята. Ожидайте, пока она пройдет модерацию.', 'game/index');
        }
      }
      else
      {
        $this->errorMessage('Не удалось подять заявку: игра или заявка с таким названием уже существует.');
      }
    }
  }
  
  public function executeNewManual(sfWebRequest $request)
  {
    $this->_teams = new Doctrine_Collection('Team');
    foreach (Doctrine::getTable('Team')->findAll() as $team)
    {
      if ($team->canBeManaged($this->sessionWebUser))
      {
        $this->_teams->add($team);
      }
    }
    $this->errorRedirectIf($this->_teams->count() <= 0, 'Нет команд, от лица которых вы можете подать заявку на создание игры.');
  }
  
  public function executeAcceptManual(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->errorRedirectUnless(
        $gameCreateRequest = GameCreateRequest::byId($request->getParameter('id')),
        'Заявка на создание игры не найдена',
        'game/index'
    );
    
    $this->errorRedirectUnless(
        $this->sessionWebUser->can(Permission::GAME_MODER, 0),
        'Создать игру по заявке может только модератор игр.',
        'game/index'
    );

    $this->errorRedirectUnless(
        Utils::byField('Game', 'name', $gameCreateRequest->name) === false,
        'Не удалось создать игру: игра '.$gameCreateRequest->name.' уже существует.',
        'team/index'
    );
    
/*    $game = new Game;
    $game->name = $gameCreateRequest->name;
    $game->team_id = $gameCreateRequest->team_id;
    $game->save(); //Требуется, так как иначе не удастся включить капитана.
    $gameCreateRequest->delete();*/
    
    $game = GameCreateRequest::doCreate($gameCreateRequest);
    
    $this->successRedirect('Игра '.$game->name.' успешно создана.', 'game/index');
  }

}
?>