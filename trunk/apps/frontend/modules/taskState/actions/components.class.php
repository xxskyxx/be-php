<?php
class taskStateComponents extends sfComponents
{
  /**
   * Отображает список ответов, как его видит команда на странице задания.
   *
   * @param   string    $taskStateId      Id состояния задания
   */
  public function executeAnswersForTeam()
  {
    $this->makeAnswersLists($this->taskStateId);
    $this->_badAnswersLeft = $this->_taskState->Task->try_count_local - $this->_badAnswers->count();
  }

  /**
   * Отображает список ответов, как его видит организатор игры.
   *
   * @param   string    $taskStateId      Id состояния задания
   */
  public function executeAnswersForGameManager()
  {
    $this->makeAnswersLists($this->taskStateId);
  }

  /**
   * Отображает список ответов для телеметрии игры.
   *
   * @param   string    $taskStateId      Id состояния задания
   */
  public function executeAnswersForReport()
  {
    $this->_timeSortedPostAnswers = Doctrine::getTable('PostedAnswer')
        ->createQuery('pa')
        ->select()
        ->where('task_state_id = ?', $this->taskStateId)
        ->orderBy('post_time')
        ->execute();
  }

  /**
   * Подготавливает списки ответов различного типа.
   *
   * @param   integer   $id   Ключ состояния задания.
   */
  protected function makeAnswersLists($id)
  {
    $this->_taskState = TaskState::byId($id);
    if ($this->_taskState)
    {
      $this->_restAnswers = $this->_taskState->getRestAnswers();
      $this->_goodAnswers = $this->_taskState->getGoodPostedAnswers();
      $this->_beingVerifiedAnswers = $this->_taskState->getBeingVerifiedPostedAnswers();
      $this->_badAnswers = $this->_taskState->getBadPostedAnswers();
    }
    else
    {
      $this->_restAnswers = new Doctrine_Collection('Answer');
      $this->_goodAnswers = new Doctrine_Collection('PostedAnswer');
      $this->_beingVerifiedAnswers = new Doctrine_Collection('PostedAnswer');
      $this->_badAnswers = new Doctrine_Collection('PostedAnswer');
    }
  }
}
?>