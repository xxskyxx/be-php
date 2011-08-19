<?php

/**
 * GameCreateRequest form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GameCreateRequestForm extends BaseGameCreateRequestForm
{
  public function configure()
  {
    unset($this['tag']);
    //Команда будет устанавливаться принудительно.
    unset($this['team_id']);
    $this->setWidget('team_id', new sfWidgetFormInputHidden());
    $this->setValidator('team_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Team'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(
        array(
          'name' => 'Название:',
          'description' => 'Сообщение модератору:'
        )
    );
    $this->getWidgetSchema()->setHelps(
        array(
          'name' => 'Чем короче тем лучше',
          'description' => ''
        )
    );    
  }
}
