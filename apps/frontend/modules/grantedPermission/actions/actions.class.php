<?php

/**
 * grantedPermission actions.
 *
 * @package    sf
 * @subpackage grantedPermission
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class grantedPermissionActions extends MyActions
{

  public function preExecute()
  {
    parent::preExecute();
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::PERMISSION_MODER, 0),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать список игр'));
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($webUser = WebUser::byId($request->getParameter('webUserId', 0)), 'Не указан пользователь');
    $newGrantedPermission = new GrantedPermission;
    $newGrantedPermission->WebUser = $webUser;
    $this->form = new grantedpermissionForm($newGrantedPermission);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new grantedpermissionForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($grantedPermission = GrantedPermission::byId($request->getParameter('id')), 'Разрешение не найдено.');
    $wasDeny = $grantedPermission->deny;
    $wasWebUser = $grantedPermission->WebUser;
    $wasPermission = $grantedPermission->Permission;
    $wasFilterId = $grantedPermission->filter_id;
    if (is_string($res = $wasWebUser->revoke($wasPermission, $wasFilterId, $this->sessionWebUser)))
    {
      $this->errorRedirect($wasDeny
              ? 'Снять запрет не удалось: '.$res
              : 'Отозвать право не удалось: '.$res);
    }
    $this->successRedirect($wasDeny
            ? 'Запрет успешно снят.'
            : 'Право успешно отозвано.'
    );
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      if (is_string($res = $object->deny
              ? $object->WebUser->deny($object->Permission, $object->filter_id, $this->sessionWebUser)
              : $object->WebUser->grant($object->Permission, $object->filter_id, $this->sessionWebUser)))
      {
        $this->errorRedirect('Назначить право или запрет не удалось: '.$res);
      }
      else
      {
        $this->successRedirect(
            (
            $object->deny
                ? 'Запрет успешно установлен. '
                : 'Право успешно назначено.'
            ),
            'webUser/show?id='.$object->web_user_id
        );
      }
    }
    else
    {
      $this->errorMessage('Назначить право или запрет не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }

}
