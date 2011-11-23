<?php

/**
 * webUser actions.
 *
 * @package    sf
 * @subpackage webUser
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class webUserActions extends MyActions
{

  public function executeIndex(sfWebRequest $request)
  {
    //Просматривать список пользователей можно в любом случае.
    $this->_sessionWebUserId = $this->sessionWebUser->id;
    $this->_currentRegion = Region::byIdSafe($this->session->getAttribute('region_id'));
    $this->_webUsers = WebUser::byRegion($this->_currentRegion);
  }

  public function executeShow(sfWebRequest $request)
  {
    //Просматривать пользователя можно в любом случае,
    //но на самой странице просмотра будут дополнительные ограничения
    $this->_webUser = WebUser::byId($request->getParameter('id'));
    $this->forward404Unless($this->_webUser, 'Анкета не найдена.');
    //Подготовим данные о правах:
    $this->_isSelf = ($this->_webUser->id == $this->sessionWebUser->id);
    $this->_isModerator = $this->sessionWebUser->can(Permission::WEB_USER_MODER, $this->_webUser->id);
    $this->_isPermissionModerator = $this->sessionWebUser->can(Permission::PERMISSION_MODER, 0);
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->webUser = WebUser::byId($request->getParameter('id')), 'Анкета не найдена.');
    $this->errorRedirectUnless($this->webUser->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'редактировать анкету'));
    $this->form = new webUserForm($this->webUser);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($this->webUser = WebUser::byId($request->getParameter('id')), 'Анкета не найдена.');
    $this->form = new webUserForm($this->webUser);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($this->webUser = WebUser::byId($request->getParameter('id')), 'Анкета не найдена.');
    $this->errorRedirectIf($this->webUser->id == $this->sessionWebUser->id, 'Cуицид не приветствуется. Обратитесь к администратору.');
    $this->errorRedirectUnless(WebUser::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'удалять пользователя'));
    $this->errorRedirectIf($this->webUser->can(Permission::ROOT) && (!$this->sessionWebUser->can(Permission::ROOT)), 'Удалять администраторов может только администратор.');
    $this->webUser->delete();
    $this->successRedirect('Пользователь успешно удален', 'webUser/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $this->errorRedirectUnless($object->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'изменять анкету'));
      $object = $form->save();
      $this->successRedirect('Анкета '.$object->login.' успешно сохранена.', 'webUser/show?id='.$object->id);
    }
    else
    {
      $this->errorMessage('Сохранить анкету не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

  public function executeEnable(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $request->checkCSRFProtection();
    $this->errorRedirectUnless(WebUser::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'разблокировать пользователя'));
    $this->forward404Unless($webUser = WebUser::byId($request->getParameter('id')), 'Анкета не найдена.');
    $webUser->is_enabled = true;
    $webUser->save();
    $this->successRedirect('Пользователь успешно разблокирован');
  }

  public function executeDisable(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $request->checkCSRFProtection();
    $this->errorRedirectUnless(WebUser::isModerator($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'блокировать пользователя'));
    $this->forward404Unless($webUser = WebUser::byId($request->getParameter('id')), 'Анкета не найдена.');
    $webUser->is_enabled = false;
    $webUser->save();
    $this->successRedirect('Пользователь успешно заблокирован');
  }

}
