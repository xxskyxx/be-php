<?php

/**
 * TeamState
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TeamState extends BaseTeamState implements IStored, IAuth
{
  const TEAM_WAIT_GAME = 0;
  const TEAM_WAIT_START = 100;
  const TEAM_WAIT_TASK = 200;
  const TEAM_HAS_TASK = 300;
  const TEAM_FINISHED = 900;

  //// IStored ////

  static function all()
  {
    return Utils::all('TeamState');
  }

  static function byId($id)
  {
    return Utils::byId('TeamState', $id);
  }

  //// IAuth ////

  static function isModerator(WebUser $account)
  {
    return Game::isModerator($account);
  }

  function canBeManaged(WebUser $account)
  {
    return $this->Game->canBeManaged($account);
  }

  function canBeObserved(WebUser $account)
  {
    return $this->Game->canBeObserved($account) || $this->Team->canBeObserved($account);
  }

  //// Public ////
  // Info

  /**
   * Проверяет, обладает ли пользователь правом обновлять состояние команды
   *
   * @param WebUser $account
   */
  public function canUpdateState(WebUser $account)
  {
    if (!$this->Game->teams_can_update)
    {
      // Пересчет состояния допустим только руководителем игры, проверим
      return $this->canBeManaged($account);
    }
    else
    {
      // Пересчет состояния допустим как руководителем, так и любым игроком
      return ($this->Team->isPlayer($account)) || $this->canBeManaged($account);
      /* Проверка на то, что команда зарегистрирована не нужна, так как
       * экземпляр состояния команды создается только при регистрации.
       */
    }
  }

  /**
   * Описывает состояние команды по коду статуса
   *
   * @param   integer   $aStatus  Код статуса
   * @return  string
   */
  public function describeStatus()
  {
    switch ($this->status)
    {
      case TeamState::TEAM_WAIT_GAME: return 'Ждет игры';
        break;
      case TeamState::TEAM_WAIT_START: return 'Ждет старта';
        break;
      case TeamState::TEAM_WAIT_TASK: return 'Ждет задания';
        break;
      case TeamState::TEAM_HAS_TASK: return 'Есть задание';
        break;
      case TeamState::TEAM_FINISHED: return 'Финишировала';
        break;
      default: return 'Неизвестно';
        break;
    }
  }

  /**
   * Возвращает фактическое время старта команды с учетом фактического времени старта игры (Ввремя Unix).
   *
   * @return  integer
   */
  public function getActualStartDateTime()
  {
    return ($this->Game->started_at > 0)
        ? $this->Game->started_at + $this->start_delay * 60
        : Timing::strToDate($this->Game->start_datetime) + $this->start_delay * 60;
  }

  /**
   * Возвращает фактическое время финиша команды (время Unix).
   * Только для справок!
   *
   * @return  integer
   */
  public function getTeamStopTime()
  {
    if ($this->started_at <= 0)
    {
      return 0;
    }
    return time() + ($this->Game->time_per_game * 60 - $this->getGameSpentTimeCurrent());
    //TODO: Учесть корректировки доступного игрового времени.
  }

  /**
   * Возвращает личное время команды, затраченное на игру (время Unix).
   * Основа для определения момента финиша команды!
   *
   * @return  integer
   */
  public function getGameSpentTimeCurrent()
  {
    if ($this->started_at == 0)
    {
      return 0;
    }
    // Рассчитаем затраченное на игре время как сумму времен известных заданий.
    // Завершенные значения вернут данные из БД, текущее задание само сосчитает.
    // В БД затраченное время хранится с учетом корректировок, т.е. если
    // время задания не входит в игровое, там хранится 0.
    $gameSpentTime = 0;
    foreach ($this->taskStates as $taskState)
    {
      $gameSpentTime += $taskState->getTaskSpentTimeCurrent();
    }
    return $gameSpentTime;
  }

  /**
   * Возвращает статус текущего задания, если оно есть, иначе false.
   * Внимание! Технические подробности:
   * Если ссылка на текущее задание окажется битая, то сначала попытается ее
   * исправить и при успехе вернет задание;
   * если исправить не удастся, обнулит ссылку и вернет false.
   *
   * @return  TaskState
   */
  public function getCurrentTaskState()
  {
    if ($this->task_state_id <= 0)
    {
      return false;
    }
    //Попробуем найти по ссылке
    $res = TaskState::byId($this->task_state_id);
    if (!$res)
    {
      //Ссылка на состояние задания устарела. Удалим ее.
      $this->task_state_id = 0;
      $res = false;
      // Внимание! Ссылка могла устареть, если состояние БД было восстановелено из архива.
      // Т.е. ссылка неверная, а реально у команды есть текущее задание.
      // Это задание можно найти по отсутствию признака закрытия. Попробуем.
      foreach ($this->taskStates as $taskState)
      {
        if (!$taskState->closed)
        {
          //Нашли незакрытое задание, запомним его как текущее.
          $this->task_state_id = $taskState->id;
          $this->save();
          $res = $taskState;
          break;
        }
      }
    }
    return $res;
  }

  /**
   * Возвращает статус последнего завершенного задания, если оно есть, иначе false.
   *
   * @return  TaskState
   */
  public function getLastDoneTaskState()
  {
    $res = false;
    if ($this->taskStates->count() <= 0)
    {
      return false;
    }
    $doneAt = 0;
    foreach ($this->taskStates as $taskState)
    {
      if ($taskState->closed
          && ($doneAt <= $taskState->done_at))
      {
        $doneAt = $taskState->done_at;
        $res = $taskState;
      }
    }
    return $res;
  }

  /**
   * Возвращает статус наиболее позднего задания, с которым ознакомилась команда.
   * Это задание может быть как завершенным, так и активным.
   *
   * @return  TaskState   Или false, если команда только приступила к игре.
   */
  public function getLastKnownTaskState()
  {
    $currentTask = $this->getCurrentTaskState();
    if ( $currentTask )
    {
      return $currentTask;
    }
    elseif ($lastDoneTask = $this->getLastDoneTaskState())
    {
      return $lastDoneTask;
    }
    else
    {
      return false;
    }
  }

  /**
   * Возвращает приоритет перехода на указанное задание.
   * ВНИМАНИЕ! Не проверяет задание на допустимость перехода.
   *
   * @param   Task      $task   Задание, приоритет перехода на которое надо посчитать.
   *
   * @return  integer
   */
  public function getPriorityOfTask(Task $task)
  {
    $baseTask = $this->getLastKnownTaskState();
    if ( ! $baseTask)
    {
      //Команда только стартовала, поэтому все определяется собственным приоритетом задания.
      return $task->getPrioritySelf();
    }
    else
    {
      //TODO: Здесь нужна корректировка приоритета, зависящая от команды и ее истории заданий.
      return $baseTask->Task->getPriorityOn($task);
    }
  }

  /**
   * Возвращает список всех доступных для выдачи заданий.
   * Учитывает фильтры переходов.
   * Если последнее известное задание не закончилось,
   * то фильтры применяются для случая неуспешного завершения задания.
   *
   * @return  Doctrine_Collection   Или false, если нет доступных заданий.
   */
  public function getTasksAvailableAll()
  {
    return $this->getAvailableTasks(false);
  }
  
  /**
   * Возвращает список всех заданий, доступных для ручного выбора.
   * Учитывает фильтры переходов.
   * Если последнее известное задание не закончилось,
   * то фильтры применяются для случая неуспешного завершения задания.
   *
   * @return  Doctrine_Collection   Или false, если нет доступных заданий.
   */
  public function getTasksAvailableForManualSelect()
  {
    return $this->getAvailableTasks(true);
  }
  
  /**
   * Возвращает результаты команды в виде массива:
   * - ключи:
   *    - 'id' - БД-ключ команды
   *    - 'points' - набранные очки
   *    - 'time' - затраченное время
   * - данные - соответствущие значения
   */
  public function getTeamResults()
  {
    $res = array();
    $res['id'] = $this->team_id;
    $res['points'] = 0;
    $res['time'] = 0;
    foreach ($this->taskStates as $taskState)
    {
      $taskRes = $taskState->getTaskResults();
      $res['points'] += $taskRes['points'];
      $res['time'] += $taskRes['time'];
    }
    return $res;
  }

  /**
   * Ищет состояние задания, соответствующее указанному заданию.
   *
   * @param   Task              $task   Задание на проверку.
   * @return  Doctrine_record           Или false, если команда не знает задания.
   */
  public function findKnownTaskState(Task $task)
  {
    foreach ($this->taskStates as $taskState)
    {
      if ($task->id == $taskState->task_id)
      {
        return $taskState;
      }
    }
    return false;
  }

  // Action

  /**
   * Обновляет состояние команды (сохраняет в БД).
   *
   * @param   WebUser   $actor      Исполнитель
   * @return  mixed                 True при успехе, иначе строка с ошибкой.
   */
  public function updateState(WebUser $actor)
  {
    if (!$this->Game->isActive()
        || !$this->isPlayingNow()
        || !Timing::isExpired(time(), Game::MIN_UPDATE_INERVAL, $this->team_last_update))
    {
      return true;
    }

    if (!$this->canUpdateState($actor))
    {
      return Utils::cannotMessage($actor->login, 'обновлять состояние команды');
    }

    $res = 'Неизвестная ошибка обновления состояния команды '.$this->Team->name;

    // Возможно игра уже закончилась.
    if ($this->Game->status >= Game::GAME_FINISHED)
    {
      if ($this->status <= TeamState::TEAM_WAIT_TASK)
      {
        // Нет текущего задания, можно финишировать сразу.
        $res = $this->finish($actor);
      }
      else
      {
        // Разберемся, что можно сделать с текущим заданием.
        if ($currentTaskStatus = $this->getCurrentTaskState())
        {
          if ($currentTaskStatus->status < TaskState::TASK_ACCEPTED)
          {
            // Задание еще неизвестно команде, просто прекратим его
            $this->abandonTask($actor);
            // и финишируем команду
            $res = $this->finish($actor);
          }
          else
          {
            // Ничего не делаем, обновление задания само догадается о завершении игры.
          }
        }
        else
        {
          // Задание должно быть, но не найдено, просто финишируем команду.
          $res = $this->finish($actor);
        }
      }
    }

    switch ($this->status)
    {

      // Команда ждет начала игры.
      case TeamState::TEAM_WAIT_GAME:
        if ($this->Game->isActive())
        {
          // Если игра началась, то команда должна дождаться своей очереди на старт.
          $this->status = TeamState::TEAM_WAIT_START;
        }
        $res = true;
        break;

      // Команда ждет своей очереди для старта.
      case TeamState::TEAM_WAIT_START:
        if (($this->Game->isActive()) && (time() >= $this->getActualStartDateTime()))
        {
          $res = $this->start($actor);
        }
        else
        {
          $res = true;
        }
        break;

      // Команда ждет следующего задания.
      case TeamState::TEAM_WAIT_TASK:
        //Если игровое время исчерпано
        if ($this->getGameSpentTimeCurrent() >= $this->Game->time_per_game * 60)
        {
          //То выдавать следующее задание нельзя, финишируем.
          $res = $this->finish($actor);
        }
        else
        {
          if ($this->task_id > 0)
          {
            // Следующее задание уже назначено, надо его сделать текущим.
            $nextTask = $this->Task;
            // Задание попытаемся выдать только один раз, если не получится, его надо будет назначить заново.
            $this->Task = null;
            $res = $this->giveTask($nextTask, $actor);
          }
          else
          {
            // Следующее задание еще не назначено.
            $availableTasks = $this->getTasksAvailableAll();
            if ( ! $availableTasks)
            {
              // У команды нет доступных заданий, значит она завершила игру.
              $res = $this->finish($actor);
            }
            else
            {
              $availableTasksManual = $this->getTasksAvailableForManualSelect();
              if ( ! $availableTasksManual)
              {
                // У команды нет заданий для выбора вручную, назначим автоматически.
                if ($this->ai_enabled)
                {
                  $this->autoSelectNextTaskFrom($availableTasks);
                }
              }
              $res = true;
            }
          }
        }
        break;

      // Команда выполняет задание
      case TeamState::TEAM_HAS_TASK:
        if ($currentTaskStatus = $this->getCurrentTaskState())
        {
          if ($currentTaskStatus->status >= TaskState::TASK_DONE)
          {
            // Задание завершено с каким-либо результатом.
            $res = $this->closeTask($actor);
          }
          else
          {
            // Держать здесь! Выполнять только ПОСЛЕ проверки на завершение.
            $res = $currentTaskStatus->updateState($actor);
          }
        }
        else
        {
          //Текущее задание потеряно. Будем ждать следующего.
          $this->status = TeamState::TEAM_WAIT_TASK;
          $res = true;
        }
        break;

      case TeamState::TEAM_FINISHED:
      default:
        $res = true;
        break;
    }

    $this->team_last_update = time();
    $this->save();
    return $res;
  }

  /**
   * Сбрасывает состояние команды на предстартовое.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor      Исполнитель
   * @return  mixed                 True при успехе, иначе строка с ошибкой.
   */
  public function reset(WebUser $actor)
  {
    // Сбрасывать состояние может только руководитель
    if (!$this->Game->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }

    // Сбросим счетчик времени
    // Сбросим текущее задание
    $this->task_state_id = 0;
    // Удалим все достижения
    foreach ($this->taskStates as $taskState)
    {
      $taskState->delete();
    }

    $this->status = TeamState::TEAM_WAIT_GAME;
    $this->team_last_update = time();
    return true;
  }

  /**
   * Выполняет старт команды.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor      Исполнитель
   * @return  mixed                 True при успехе, иначе строка с ошибкой.
   */
  public function start(WebUser $actor)
  {
    if (!$this->canUpdateState($actor))
    {
      return Utils::cannotMessage($actor->login, 'обновлять состояние команды');
    }
    if ($this->status > TeamState::TEAM_WAIT_START)
    {
      return 'Команда '.$this->Team->name.' уже стартовала в игре '.$this->Game->name;
    }
    // Запомним реальное время старта
    $this->started_at = time();
    // Сбросим текущее задание
    $this->task_state_id = 0;
    // Разрешим получать задания
    $this->status = TeamState::TEAM_WAIT_TASK;

    $this->team_last_update = time();
    return true;
  }

  /**
   * Фиксирует финиш команды.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor      Исполнитель
   * @return  mixed                 True при успехе, иначе строка с ошибкой.
   */
  public function finish(WebUser $actor)
  {
    if (!$this->canUpdateState($actor))
    {
      return Utils::cannotMessage($actor->login, 'обновлять состояние команды');
    }
    if ($this->status >= TeamState::TEAM_FINISHED)
    {
      return 'Команда '.$this->Team->name.' уже финишировала в игре '.$this->Game->name;
    }
    // Определим время финиша по времени выполнения последнего успешного задания
    $this->finished_at = 0;
    foreach ($this->taskStates as $taskState)
    {
      if (($taskState->status == TaskState::TASK_DONE_SUCCESS)
          && ($this->finished_at < $taskState->done_at))
      {
        $this->finished_at = $taskState->done_at;
      }
    }
    // Если не нашли ни одного выполненного задания, то финиш по времени остановки игры или по текущему.
    if ($this->finished_at == 0)
    {
      $this->finished_at = ($this->Game->finished_at > 0)
          ? $this->Game->finished_at
          : time();
    }
    $this->status = TeamState::TEAM_FINISHED;

    $this->team_last_update = time();
    return true;
  }

  /**
   * Назначает команде следующее задание (не путать с выдачей нового).
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   * Внимание: Задание не считается стартовавшим, пока команда его не увидит!
   *
   * @param   mixed     $task   Задание или null для отмены выбора
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function setNextTask($task, WebUser $actor)
  {
    if ($task == null)
    {
      // Выполняется отмена выбора следующего задания, 
      // ее может сделать только руководитель игры
      if ( ! $this->canUpdateState($actor))
      {
        return Utils::cannotMessage($actor->login, 'назначать состояние команды');
      }
      $this->Task = null;
      return true;
    }
    else
    {
      if ($this->findKnownTaskState($task) !== false)
      {
        return 'Команда '.$this->Team->name.' уже получала задание '.$task->name;
      }
      
      if ($this->canUpdateState($actor))
      {
        // Руководитель игры может назначить любое задание из неизвестных
        $this->task_id = $task->id;
        return true;
      }
      
      //Возможно это капитан команды вручную выбирает следующее задание
      //Если он не капитан, то это делать нельзя
      if ( ! $this->Team->canBeManaged($actor))
      {
        // Руководитель игры может назначить любое задание из неизвестных
        return Utils::cannotMessage($actor->login, 'выбирать следующее задание');
      }
      
      //Выбор вручную возможен только при наличии разрешенных для этого заданий
      $availableTasksManual = $this->getTasksAvailableForManualSelect();
      if ($availableTasksManual->count() > 0)
      {
        //Если задание не входит в список доступных, то его не выбрать
        if ( ! Task::isTaskInList($task, $availableTasksManual))
        {
          return 'Это задание недоступно для ручного выбора командой.';
        }
        
        // Назначаем следующее задание
        $this->task_id = $task->id;
        return true;
      }
      else
      {
        return 'Нет заданий доступных для ручного выбора.';
      }
    }
  }

  /**
   * Выдает команде задание к исполнению.
   * ВНИМАНИЕ: Сохраняет в БД только статус нового задания. Save() выполняет вызывающая сторона.
   *
   * @param   Task      $task   Задание
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function giveTask(Task $task, WebUser $actor)
  {
    if (!$this->canUpdateState($actor))
    {
      return Utils::cannotMessage($actor->login, 'обновлять состояние команды');
    }
    if ($currentTaskStatus = $this->getCurrentTaskState())
    {
      return 'Команда '.$this->Team->name.' еще не закончила задание '.$currentTaskStatus->Task->name;
    }
    if ($this->findKnownTaskState($task) !== false)
    {
      return 'Команда '.$this->Team->name.' уже получала задание '.$task->name;
    }
    $newStatus = new TaskState();
    $newStatus->team_state_id = $this->id;
    $newStatus->task_id = $task->id;
    $newStatus->given_at = time();
    $newStatus->started_at = 0;
    $newStatus->accepted_at = 0;
    $newStatus->task_idle_time = 0;
    $newStatus->done_at = 0;
    $newStatus->closed = false;
    $newStatus->status = TaskState::TASK_GIVEN;
    $newStatus->task_time_spent = 0;
    $newStatus->task_last_update = time();
    $newStatus->save(); //Опасно!! Но по другому не получится получить реальный id новой записи для... (1)
    $this->taskStates->add($newStatus);
    $this->task_state_id = $newStatus->id; //(1)... использования здесь

    $this->status = TeamState::TEAM_HAS_TASK;
    $this->team_last_update = time();
    return true;
  }

  /**
   * Подтверждает завершение задания.
   * Не меняет уже имеющийся статус задания, который отражает результат выполнения задания.
   * ВНИМАНИЕ: Сохраняет в БД только статус закрытого задания. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function closeTask(WebUser $actor)
  {
    if (!$this->canUpdateState($actor))
    {
      return Utils::cannotMessage($actor->login, 'обновлять состояние команды');
    }
    if ($currentTaskStatus = $this->getCurrentTaskState())
    {
      if ($currentTaskStatus->status < TaskState::TASK_DONE)
      {
        return 'Команда '.$this->Team->name.' еще выполняет задание '.$currentTaskStatus->Task->name;
      }
    }
    else
    {
      return 'У команды '.$this->Team->name.' нет текущего задания';
    }

    $currentTaskStatus->closed = true;
    $currentTaskStatus->save();
    $this->task_state_id = 0;

    $this->status = TeamState::TEAM_WAIT_TASK;
    return true;
  }

  /**
   * Отменяет текущее задание команды. Если она с ним уже ознакомилась, то
   * завершает задание с признаком отмены. Если не успела - просто убирает
   * задание из истории.
   * ВНИМАНИЕ: Сохраняет в БД состояние и команды и задания.
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function abandonTask(WebUser $actor)
  {
    // Отменять состояние может только руководитель
    if (!$this->Game->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }
    if (!($currentTaskStatus = $this->getCurrentTaskState()))
    {
      return true;
    }

    //Если задание не было просмотрено, то его надо просто убрать
    if ($currentTaskStatus->status < TaskState::TASK_ACCEPTED)
    {
      $currentTaskStatus->delete();
      $this->task_state_id = 0;
      $this->save();
      $this->status = TeamState::TEAM_WAIT_TASK;
    }
    //Задание было просмотрено, его надо завершить корректно
    elseif ($currentTaskStatus->status >= TaskState::TASK_DONE)
    {
      return 'Текущее задание уже завершено.';
    }
    else
    {
      $currentTaskStatus->status = TaskState::TASK_DONE_ABANDONED;
      $currentTaskStatus->done_at = time();
      $currentTaskStatus->task_time_spent = 0;
      $currentTaskStatus->save();
      //Задание будет закрыто в следующем цикле пересчета.
    }

    return true;
  }

  //// Self ////

  /**
   * Выполняет выбор следующего задания на основе текущей ситуации.
   * Результат устанавливается в качестве следующего задания команды.
   * Если задание уже установлено - не меняет его.
   * Если задание выбрать не удастся - ничего не делает.
   * 
   * @param   Doctrine_Collection   $availableTasks   Задания, из которых выбирать следующее.
   */
  protected function autoSelectNextTaskFrom(Doctrine_Collection $availableTasks)
  {
    //Если команде уже назначено следующее задание
    //или список потенциальных заданий пуст
    if (($this->task_id > 0) || ($availableTasks->count() <= 0))
    {
      return;
    }
    //Определим текущие приоритеты заданий и максимальный из них.
    $candidates = array();
    $candidatesCount = 0;
    //Проинициализируем maxPriority реальным значением,
    //а то что-то сравнение с null норовит отбросить знак
    //и отрицательные приоритеты считать положительными.
    $maxPriority = $this->getPriorityOfTask($this->Game->tasks->getFirst());
    foreach ($availableTasks as $task)
    {
      $candidate = array();
      $candidate['task_id'] = $task->id;
      $priority = $this->getPriorityOfTask($task);
      if ($priority !== false)
      {
        $candidate['priority'] = $priority;
        array_push($candidates, $candidate);
        $candidatesCount++;

        if ($priority > $maxPriority)
        {
          $maxPriority = $priority;
        }
      }
    };
    // Нет ни одного доступного задания?
    if ($candidatesCount == 0)
    {
      // Просто выход, это уже не забота ИИ
      return;
    }
    // Доступно только одно задание?
    if ($candidatesCount == 1)
    {
      // Тогда выбирать не приходится.
      $this->task_id = $candidates[0]['task_id'];
      $this->save();
      return;
    }

    // Отберем задания с максимальным приоритетом и сосчитаем их.
    $finalCandidates = array();
    $finalCandidatesMaxIndex = 0;
    foreach ($candidates as $candidate)
    {
      if ($candidate['priority'] == $maxPriority)
      {
        array_push($finalCandidates, $candidate);
        $finalCandidatesMaxIndex++;
      }
    }

    // Выберем случайное из отобранных заданий в качестве следующего.
    $this->task_id = $finalCandidates[rand(0, $finalCandidatesMaxIndex - 1)]['task_id'];
    $this->save();
    return;
  }

  /**
   * Проверяет, играет ли команда в данный момент
   */
  protected function isPlayingNow()
  {
    return $this->status < TeamState::TEAM_FINISHED;
  }

  /**
   * Возвращает список известных команде заданий.
   *
   * @return  Doctrine_Collection<Task>
   */
  protected function getKnownTasks()
  {
    $result = new Doctrine_Collection('Task');
    foreach ($this->Game->tasks as $task)
    {
      $knownTaskState = $this->findKnownTaskState($task);
      if ($knownTaskState)
      {
        $result->add($task);
      }
    }
    return $result;
  }

  /**
   * Возвращает список доступных для выдачи заданий: все или только для ручного выбора.
   * Учитывает фильтры последнего известного задания.
   * Если последнее известное задание не закончилось,
   * то фильтры применяются для случая неуспешного завершения задания.
   * 
   * @param   boolean               $forManualSelectOnly  Только задания для ручного выбора.
   *
   * @return  Doctrine_Collection   Или false, если нет доступных заданий.
   */
  protected function getAvailableTasks($forManualSelectOnly)
  {
    $allTasks = $this->Game->tasks;
    $knownTasks = $this->getKnownTasks();
    $unknownTasks = Task::excludeTasks($allTasks, $knownTasks);

    $lastKnownTaskState = $this->getLastKnownTaskState();
    if ( ! $lastKnownTaskState ) 
    {
      // Команда еще не делала ни одного задания.
      if ($forManualSelectOnly)
      {
        return false; // Первое задание не может быть выбрано вручную.
      }
      return $unknownTasks;      
    }
    $isLastKnownTaskSucceeded = $lastKnownTaskState->status == TaskState::TASK_DONE_SUCCESS;
    $tasksPassedTransitionsFilter = $lastKnownTaskState->Task->getNextTasks($isLastKnownTaskSucceeded, $forManualSelectOnly);
    if ($tasksPassedTransitionsFilter->count() == 0)
    {
      $result = $unknownTasks;
    }
    else
    {
      $result = Task::excludeTasks($tasksPassedTransitionsFilter, $knownTasks);
    }
    return ($result->count() > 0) ? $result : false;
  }
  
}