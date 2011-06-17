<?php

/**
 * TaskConstraint
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TaskConstraint extends BaseTaskConstraint implements IStored, IAuth
{

  //// IStored ////

  static function all()
  {
    return Utils::all('TaskConstraint');
  }

  static function byId($id)
  {
    return Utils::byId('TaskConstraint', $id);
  }

  //// IAuth ////

  static function isModerator(WebUser $account)
  {
    return Game::isModerator($account);
  }

  function canBeManaged(WebUser $account)
  {
    return $this->Task->canBeManaged($account);
  }

  function canBeObserved(WebUser $account)
  {
    return $this->Task->canBeObserved($account);
  }

  // Public
  
  /**
   * Возвращает целевое задание, если его можно найти.
   * 
   * @return Doctrine_Record Или false, если не найдено.
   */
  public function getTargetTaskSafe()
  {
    return Task::byId($this->target_task_id);
  }

}