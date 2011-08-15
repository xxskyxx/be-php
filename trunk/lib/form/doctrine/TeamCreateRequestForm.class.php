<?php

/**
 * TeamCreateRequest form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TeamCreateRequestForm extends BaseTeamCreateRequestForm
{
  public function configure()
  {
    //Пользователь будет устанавливаться принудительно.
    unset($this['web_user_id']);
    $this->setWidget('web_user_id', new sfWidgetFormInputHidden());
    $this->setValidator('web_user_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('WebUser'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(
        array(
          'name' => 'Краткое название:',
          'full_name' => 'Полное название:',
          'description' => 'Сообщение модератору:'
        )
    );
    $this->getWidgetSchema()->setHelps(
        array(
          'name' => 'Чем короче тем лучше',
          'full_name' => '',
          'description' => ''
        )
    );
  }
}
