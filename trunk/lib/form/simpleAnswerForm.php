<?php

class SimpleAnswerForm extends BaseForm
{

  public function setup()
  {
    parent::setup();

    $this->setWidgets(
        array(
            'value' => new sfWidgetFormInputText()
        )
    );

    $this->setValidators(
        array
            (
            'value' => new sfValidatorString(
                array
                    (
                    'max_length' => 32,
                    'required' => false
                )
            )
        )
    );

    $this->getWidgetSchema()->setNameFormat('simpleAnswer[%s]');
    $this->getWidget('value')->setAttribute('size', 5);

    $this->getWidgetSchema()->setLabels(array('value' => 'Ответ'));
  }

}

?>
