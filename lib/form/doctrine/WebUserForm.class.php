<?php

/**
 * WebUser form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class WebUserForm extends BaseWebUserForm
{

  public function configure()
  {

    unset($this['pwd_hash']); //Пароль не редактируется.
    unset($this['tag']); //Многоцелевое поле не редактируется.
    unset($this['is_enabled']); //Чтобы сами себя не блокировали.
    //Добавим проверку логина на минимальную длину.
    $this->validatorSchema['login'] = new sfValidatorAnd(array(
            $this->validatorSchema['login'],
            new sfValidatorString(array('min_length' => WebUser::MIN_NAME_LENGTH))
        ));

    //Значение активности пользователя - обязательно.
    $this->setValidator('enabled', new sfValidatorPass(array('required' => true)));
    //Регион - обязателен
    $this->getWidget('region_id')->setOption('add_empty', false);

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'login' => 'Имя:',
        'full_name' => 'Ф.И.(О.):',
        'email' => 'E-Mail:',
        'region_id' => 'Регион:'
    ));

    $this->getWidgetSchema()->setHelps(array(
        'login' => 'Лучше короткий ник-нейм',
        'full_name' => 'Фамилия и имя, отчество по желанию.',
        'email' => 'Настоятельно рекомендуется действующий.',
        'region_id' => 'Регион по умолчанию.|<span class="info">Не препятствует вступлению в команды других регионов.</span>|<span class="info">Не препятствует участию в играх других регионов.</span>'
    ));
  }

}
