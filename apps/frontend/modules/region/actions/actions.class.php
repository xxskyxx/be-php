<?php

/**
 * region actions.
 *
 * @package    sf
 * @subpackage region
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class regionActions extends myActions
{

  public function preExecute()
  {
    parent::preExecute();    
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'управлять регионами'));        
    
    $this->_regions = Doctrine_Core::getTable('Region')
      ->createQuery('r')
      ->select()
      ->where('r.id <> ?', Region::DEFAULT_REGION)
      ->orderBy('r.name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'управлять регионами'));        
    
    $this->form = new RegionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'управлять регионами'));        
    
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new RegionForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'управлять регионами'));        
    
    $this->forward404Unless($region = Doctrine_Core::getTable('Region')->find(array($request->getParameter('id'))), sprintf('Object region does not exist (%s).', $request->getParameter('id')));
    if ($region->id == Region::DEFAULT_REGION)
    {
      $this->errorRedirect('Нельзя править регион, используемый по умолчанию.', 'region/index');
    }
    $this->form = new RegionForm($region);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'управлять регионами'));        
    
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($region = Doctrine_Core::getTable('Region')->find(array($request->getParameter('id'))), sprintf('Object region does not exist (%s).', $request->getParameter('id')));
    $this->form = new RegionForm($region);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->sessionWebUser->can(Permission::ROOT, 0), Utils::cannotMessage($this->sessionWebUser->login, 'управлять регионами'));        
    
    $request->checkCSRFProtection();
    $this->forward404Unless($region = Doctrine_Core::getTable('Region')->find(array($request->getParameter('id'))), sprintf('Object region does not exist (%s).', $request->getParameter('id')));
    if ($region->id == Region::DEFAULT_REGION)
    {
      $this->errorRedirect('Нельзя удалять регион, используемый по умолчанию.', 'region/index');
    }
    $region->delete();
    $this->redirect('region/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $region = $form->save();
      $this->successRedirect('Регион успешно сохранен.', 'region/index');
    }
  }
  
  public function executeSetCurrent(sfWebRequest $request)
  {
    if ($request->isMethod(sfRequest::POST))
    {
      $this->forward404Unless($region = Region::byId($request->getParameter('id')), 'Регион не найден.');
      $this->session->setAttribute('region_id', $region->id);
      $this->successRedirect('Установлен текущий регион - '.$region->name);
    }
    else
    {
      $this->_regions = Doctrine_Core::getTable('Region')
          ->createQuery('r')
          ->select()->orderBy('r.name')
          ->execute();
      $this->_retUrlRaw = $this->retUrlRaw;
      $this->_retUrlDecoded = Utils::getReturnUrl($request);
      $this->_selfRegionId = $this->sessionWebUser->getRegionSafe()->id;
    }
  }
  
  
}
