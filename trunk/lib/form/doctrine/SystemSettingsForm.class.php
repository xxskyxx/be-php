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
      'games_announce_interval' => new sfValidatorInteger(array('required' => true)),
      'fast_user_register' => new sfValidatorBoolean(array('required' => false)),
      'email_team_create'  => new sfValidatorBoolean(array('required' => false)),
      'fast_team_create'   => new sfValidatorBoolean(array('required' => false)),
      'email_game_create'  => new sfValidatorBoolean(array('required' => false)),
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
      'games_announce_interval' => 'Интервал анонса игр:',
      'email_team_create'  => 'Cоздание команд по почте:',
      'email_game_create'  => 'Cоздание игр по почте:',
      'fast_team_create'   => 'Быстрое создание команд:',
      'fast_user_register' => 'Быстрая регистрация:',
      'notify_email_addr'  => 'Обратный адрес:',
      'contact_email_addr' => 'Адрес администраторов:',
      'smtp_host'          => 'SMTP-сервер:',
      'smtp_port'          => 'Порт:',
      'smtp_security'      => 'Шифрование:',
      'smtp_login'         => 'Аккаунт:',
      'smtp_password'      => 'Пароль:'
    ));
    $this->getWidgetSchema()->setHelps(array(
      'site_name'          => 'Показано в заголовке страницы, на главной странице и указывается в письмах-уведомлениях.',
      'site_domain'        => '<span class="warn">Обязательно без "http://".</span>',
      'games_announce_interval' => 'дней.|Анонсы игр будут публиковаться не ранее, чем за указанное число дней до игры.',
      'email_team_create'  => 'переходом по ссылке из письма.',
      'email_game_create'  => 'переходом по ссылке из письма.',
      'fast_team_create'   => 'самостоятельным утверждением заявки без подтверждения по почте.|<span class="warn">Использовать c осторожноcтью!</span>',
      'fast_user_register' => 'пользователей без подтверждения по почте.|<span class="warn">Использовать c осторожноcтью во избежание авторегистраций!</span>',
      'notify_email_addr'  => '<span class="warn">Обязательно действующий.</span>',
      'contact_email_addr' => 'Лучше действующий.',
      'smtp_host'          => '',
      'smtp_port'          => 'Обычно 25, для ssl - 465.',
      'smtp_security'      => 'Пусто, tls или ssl.',
      'smtp_login'         => '',
      'smtp_password'      => ''
    ));
  }
}
