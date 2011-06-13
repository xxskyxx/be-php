<?php

/**
 * PostedAnswer form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PostedAnswerForm extends BasePostedAnswerForm
{
  public function configure()
  {
    //Состояние задания задается принудительно
    unset($this['task_state_id']);
    $this->setWidget('task_state_id', new sfWidgetFormInputHidden());
    $this->setValidator('task_state_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TaskState'))));

    unset($this['post_time']); //Управляется автоматически
    unset($this['status']); //Управляется автоматически
    unset($this['answer_id']); //Управляется автоматически

    $this->getWidget('value')->setAttribute('size', 5);
    $this->getWidget('value')->setAttribute('required', false);

    $this->getWidgetSchema()->setLabels(array('value' => ''));
  }
}
