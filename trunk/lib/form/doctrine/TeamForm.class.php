<?php

/**
 * Team form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TeamForm extends BaseTeamForm
{

  public function configure()
  {
    if ($this->isNew())
    {
      //Добавим еще должность капитана
      $this->setWidget(
          'leader',
          new sfWidgetFormDoctrineChoice(array(
              'model' => 'WebUser',
              'add_empty' => '(пока без него)',
              'method' => 'getLogin'
          ))
      );

      $this->setValidator(
          'leader',
          new sfValidatorDoctrineChoice(array(
              'model' => 'WebUser',
              'required' => false
          ))
      );
    }

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Название:',
        'full_name' => 'Полное название:',
        'leader' => 'Капитаном будет:'
    ));
  }

}
