<?php

/**
 * SystemSettings form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SystemSettingsForm extends BaseSystemSettingsForm
{
  public function configure()
  {
    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'site_name'          => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'site_domain'        => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'notify_email_addr'  => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'contact_email_addr' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'smtp_host'          => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'smtp_port'          => new sfValidatorInteger(array('required' => true)),
      'smtp_security'      => new sfValidatorString(array('max_length' => 3, 'required' => false)),
      'smtp_login'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'smtp_password'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));
    $this->getWidgetSchema()->setLabels(array(
      'site_name'          => 'Название сайта:',
      'site_domain'        => 'Домен сайта:',
      'notify_email_addr'  => 'Обратный адрес:',
      'contact_email_addr' => 'Адрес администраторов:',
      'smtp_host'          => 'SMTP-сервер:',
      'smtp_port'          => 'Порт:',
      'smtp_security'      => 'Шифрование:',
      'smtp_login'         => 'Аккаунт:',
      'smtp_password'      => 'Пароль:'
    ));
    $this->getWidgetSchema()->setHelps(array(
      'site_name'          => '',
      'site_domain'        => 'без HTTP://',
      'notify_email_addr'  => 'обязательно действующий',
      'contact_email_addr' => 'рекомендуется действующий',
      'smtp_host'          => '',
      'smtp_port'          => 'обычно 25, для ssl - 465',
      'smtp_security'      => 'пусто, tls или ssl',
      'smtp_login'         => '',
      'smtp_password'      => ''
    ));
  }
}
