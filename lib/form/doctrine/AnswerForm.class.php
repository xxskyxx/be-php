<?php

/**
 * Answer form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AnswerForm extends BaseAnswerForm
{
  public function configure()
  {
    //Задание будет задаваться принудительно.
    unset($this['task_id']);
    $this->setWidget('task_id', new sfWidgetFormInputHidden());
    $this->setValidator('task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название',
        'value' => 'Значение',
        'info' => 'Описание'
    ));
  }

}
