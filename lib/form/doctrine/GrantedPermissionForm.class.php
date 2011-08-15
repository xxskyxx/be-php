<?php

/**
 * GrantedPermission form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GrantedPermissionForm extends BaseGrantedPermissionForm
{

  public function configure()
  {
    //Попросим поля возвращать дружественные названия.
    $this->getWidget('permission_id')->addOption('method', 'getDescription');

    //Пользователь будет устанавливаться принудительно.
    unset($this['web_user_id']);
    $this->setWidget('web_user_id', new sfWidgetFormInputHidden());
    $this->setValidator('web_user_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'permission_id' => 'Действие:',
        'filter_id' => 'Фильтр:',
        'deny' => 'Запретить:'
    ));
    $this->getWidgetSchema()->setHelps(array(
        'permission_id' => '',
        'filter_id' => 'Id объекта, на который будет право (0 - на все)',
        'deny' => ''
    ));
  }

}
