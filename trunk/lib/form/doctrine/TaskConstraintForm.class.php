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
  public function configure()
  {
    //Опорное задание будет задаваться принудительно.
    unset($this['task_id']);
    $this->setWidget('task_id', new sfWidgetFormInputHidden());
    $this->setValidator('task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));
    
    // Переназначим поле с целевым заданием, чтобы отображало только доступные для перехода задания.
    $query = Doctrine_Core::getTable('Task')
        ->createQuery('t')
        ->select()
        ->where('game_id = ?', array($this->getObject()->Task->game_id))
        ->andWhere('id <> ?', array($this->getObject()->task_id));
    $this->setWidget('target_task_id', new sfWidgetFormDoctrineChoice(array('model' => 'Task', 'add_empty' => false, 'method' => 'getName', 'query' => $query)));
    $this->setValidator('target_task_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Task'))));
        
    //Русифицируем:
    $this->getWidgetSchema()->setLabels(array(
        'priority_shift' => 'Приоритет',
        'target_task_id' => 'На задание'
    ));
    
  }
  
  public function refreshTaskId($task_id)
  {
    $query = Doctrine_Core::getTable('Task')
        ->createQuery('t')
        ->select()
        ->where('game_id = ?', array(Task::byId($task_id)->game_id))
        ->andWhere('id <> ?', array($task_id));
    $this->getWidget('target_task_id')->setOption('query', $query);
  }
}
