<?php

/**
 * Task form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TaskForm extends BaseTaskForm
{

  public function configure()
  {
    //Игра будет задаваться принудительно.
    unset($this['game_id']);
    unset($this['priority_queued']);
    $this->setWidget('game_id', new sfWidgetFormInputHidden());
    $this->setValidator('game_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Game'))));
    $this->setValidator('min_answers_to_success', new sfValidatorInteger(array('required' => 'true', 'min' => 0)));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'name' => 'Внутреннее название:',
        'public_name' => 'Открытое название:',
        'time_per_task_local' => 'Длительность:',
        'try_count_local' => 'Неверных ответов:',
        'manual_start' => 'Ручной старт:',
        'max_teams' => 'Выполняющих команд:',
        'locked' => 'Заблокировано:',
        'priority_free' => 'Когда свободно:',
        'priority_busy' => 'Когда кому-то выдано:',
        'priority_filled' => 'Когда заполнено:',
        'priority_per_team' => 'На каждую команду:',
        'min_answers_to_success' => 'Ответов для зачета:'
    ));
    
    $this->getWidgetSchema()->setHelps(array(
        'name' => 'На игре известно только организаторам.',
        'public_name' => 'Отображается игрокам и позволяет им различать задания.|<span class="warn">Не пишите сюда информацию, облегчающую разгадывание!</span>',
        'time_per_task_local' => 'мин.|<span class="info">Если пусто или "0", то будет установлено значение из свойств игры</span>.',
        'try_count_local' => 'не&nbsp;более&nbsp;...&nbsp;.|<span class="info">Если пусто или "0", то будет установлено значение из свойств игры</span>.',
        'manual_start' => 'Не препятствует автоматической выдачи задания, но после получения задания, <span class="warn">команда будет ждать, пока организаторы вручную разрешат ей старт этого задания.</span>',
        'max_teams' => 'не&nbsp;более&nbsp;...&nbsp;.|<span class="info">Если задание выдано указанному числу команд, то команды, вынужденно получившие это задание, будут ждать, пока задание не покинет одна из команд.</span>',
        'locked' => 'При блокировке задания оно не выдается автоматически, но может быть выдано вручную.|<span class="warn">Включение блокировки не вызывает автоматического прекращения задания теми командами, которые уже получили это задание.</span>',
        'priority_free' => 'Задание не выдано никому.',
        'priority_busy' => 'Задание выдано одной или более командам.',
        'priority_filled' => 'Cуммируется с "Когда кому-то выдано" когда задание заполнено.',
        'priority_per_team' => 'Умножается на число команд, получивших задание, и суммируется с "Когда кому-то выдано".',
        'min_answers_to_success' => '&gt;&nbsp;0.|Необходимое число ответов для зачета задания.|<span class="info">Если пусто или "0" задание зачитывается только по всем ответам.</span>|<span class="warn">Если команде доступно меньшее число ответов, то задание будет засчитано, когда команда соберет все доступные ей ответы.</span>'
    ));    
  }

}
