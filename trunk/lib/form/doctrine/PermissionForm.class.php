<?php

/**
 * Permission form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PermissionForm extends BasePermissionForm
{

  public function configure()
  {
    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'id' => 'Код',
        'name' => 'Название',
        'description' => 'Описание'
    ));
  }

}
