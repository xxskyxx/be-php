<?php

/**
 * TaskConstraint form.
 *
 * @package    sf
 * @subpackage form
 * @author     VozdvIN
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TaskConstraintForm extends BaseTaskConstraintForm
{

  public function buildQuery($sourceTaskId)
  {
    $gameId = Task::byId($sourceTaskId)->game_id;
    // Переназначим поле с целевым заданием, чтобы отображало только доступные для перехода задания.
    $query = Doctrine_Core::getTable('Task')
        ->createQuery('t')
        ->select()
        ->where('game_id = ?', array($gameId))
        ->andWhere('id <> ?', array($sourceTaskId));
    $this->getWidget('target_task_id')->setOption('query', $query);
    $this->setValidator('target_task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));
  }

  public function configure()
  {
    //Опорное задание будет задаваться принудительно.
    unset($this['task_id']);
    $this->setWidget('task_id', new sfWidgetFormInputHidden());
    $this->setValidator('task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));

    // Так как на данный момент id исходного задания может быть неопределен, создаем полный запрос.
    $query = Doctrine_Core::getTable('Task')->createQuery('t')->select();
    // Заменим поле с целевым заданием на список выбора.
    $this->setWidget('target_task_id', new sfWidgetFormDoctrineChoice(array('model' => 'Task', 'add_empty' => false, 'method' => 'getName', 'query' => $query)));
    $this->setValidator('target_task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));

    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'target_task_id' => 'На задание:',
        'priority_shift' => 'Приоритет:'
    ));
    $this->getWidgetSchema()->setHelps(array(
        'target_task_id' => '',
        'priority_shift' => '<> 0.|Суммируется с текущим собственным приоритетом задания.'
    ));
  }

}
