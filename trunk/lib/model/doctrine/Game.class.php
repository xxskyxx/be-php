<?php

/**
 * Game
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    sf
 * @subpackage model
 * @author     VozdvIN
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Game extends BaseGame implements IStored, IAuth
{
  const GAME_PLANNED = 0; //Запланирована
  const GAME_VERIFICATION = 100; //Игра на предстартовой проверке
  const GAME_READY = 200; //Готова к запуску
  const GAME_STEADY = 300; //Стартовала, но еще нет стартовавших команд.
  const GAME_ACTIVE = 400; //Играется
  const GAME_FINISHED = 800; //Завершена и ожидается награждение
  const GAME_ARCHIVED = 900; //Завершена и опубликованы результаты

  const VERIFY_WARN = 1;
  const VERIFY_ERR = 2;

  const MIN_UPDATE_INERVAL = 2; //Минимальное время между пересчетами состояний игры и прочих.

  //// IStored ////

  static function all()
  {
    return Utils::all('Game', 'start_datetime');
  }

  static function byId($id)
  {
    return Utils::byId('Game', $id);
  }

  //// IAuth ////

  static function isModerator(WebUser $account)
  {
    return $account->can(Permission::GAME_MODER, 0);
  }

  function canBeManaged(WebUser $account)
  {
    $res = $this->isManager($account);
    //Если разрешение еще не нашли
    if (!$res)
    {
      //Возможно пользователь может модерировать эту игру
      $res = $account->can(Permission::GAME_MODER, $this->id);
    }
    return $res;
  }

  function canBeObserved(WebUser $account)
  {
    $res = $this->isActor($account);
    //Если разрешение еще не нашли
    if (!$res)
    {
      //Возможно пользователь может принимать участие в организации этой игры
      $res = $account->can(Permission::GAME_ACTOR, $this->id)
          || $account->can(Permission::GAME_AUTHOR, $this->id)
          || $account->can(Permission::GAME_MODER, $this->id);
    }
    return $res;
  }

  //// Public ////

  // Info
  
  /**
   * Проверяет, входит ли игрок в состав команды организаторов.
   *
   * @param   WebUser  $player  Проверяемый пользователь
   * @return  boolean
   */
  public function isActor(WebUser $testedPlayer)
  {
    $res = false;
    //Если известна команда организаторов
    if ($this->team_id > 0)
    {
      //Если пользователь - организатор
      if ($this->Team->isPlayer($testedPlayer))
      {
        $res = true;
      }
    }
    return $res;    
  }
  
  /**
   * Проверяет, входит ли игрок в состав команды организаторов.
   *
   * @param   WebUser  $player  Проверяемый пользователь
   * @return  boolean
   */
  public function isManager(WebUser $testedPlayer)
  {
    $res = false;
    //Если известна команда организаторов
    if ($this->team_id > 0)
    {
      //Если пользователь - капитан организаторов
      if ($this->Team->isLeader($testedPlayer))
      {
        $res = true;
      }
    }
    return $res;    
  }
  
  /**
   * Проверяет, зарегистрировна ли команда на игру.
   *
   * @param   Team      $testingTeam  Проверяемая команда
   * @return  boolean
   */
  public function isTeamRegistered(Team $testedTeam)
  {
    foreach ($this->teamStates as $teamState)
    {
      if ($teamState->team_id == $testedTeam->id)
      {
        return true;
      }
    }
    return false;
  }

   /**
   * Проверяет, подавала ли команда заявку на игру.
   *
   * @param   Team      $testingTeam  Проверяемая команда
   * @return  boolean
   */
  public function isTeamCandidate(Team $testedTeam)
  {
    foreach ($this->gameCandidates as $gameCandidate)
    {
      if ($gameCandidate->team_id == $testedTeam->id)
      {
        return true;
      }
    }
    return false;
  }

  /**
   * Проверяет, входит ли игрок в состав какой-то из зарегистрированных команд.
   *
   * @param   WebUser  $player  Проверяемый пользователь
   * @return  boolean
   */
  public function isPlayerRegistered(WebUser $testedPlayer)
  {
    foreach ($this->teamStates as $teamState)
    {
      if ($teamState->Team->isPlayer($testedPlayer))
      {
        return true;
      }
    }
    return false;
  }

  /**
   * Описывает состояние игры по коду статуса
   *
   * @param   integer   $aStatus  Код статуса
   * @return  string
   */
  public function describeStatus()
  {
    switch ($this->status)
    {
      case Game::GAME_PLANNED: return 'Запланирована';
        break;
      case Game::GAME_VERIFICATION: return 'На проверке';
        break;
      case Game::GAME_READY: return 'Готова к старту';
        break;
      case Game::GAME_STEADY: return 'Стартует';
        break;
      case Game::GAME_ACTIVE: return 'Идет';
        break;
      case Game::GAME_FINISHED: return 'Финишировала';
        break;
      case Game::GAME_ARCHIVED: return 'Сдана в архив';
        break;
      default: return 'Неизвестно';
        break;
    }
  }

  /**
   * Проверяет, находится ли игра в активной фазе.
   *
   * @return  boolean
   */
  public function isActive()
  {
    /* Состояние GAME_FINISHED еще считается активным, так как не все команды
     * сразу узнают об окончании игры. После остановки игры нужно еще несколько
     * тактов пересчета на закрытие всех заданий.
     */
    return ($this->status >= Game::GAME_STEADY) && ($this->status <= Game::GAME_FINISHED);
  }

  /**
   * Возвращает время принудительной остановки игры с учетом корректировок (время Unix)
   *
   * @return  integer
   */
  public function getGameStopTime()
  {
    return Timing::strToDate($this->stop_datetime);
  }

  /**
   * Возвращает текущие результаты команд в виде массива:
   * - ключ - сурроганый, порядок отражает порядок команд от первого до последнего места.
   * - данные - массив:
   *    - 'id' - БД-ключ команды
   *    - 'points' - набранные очки
   *    - 'time' - потраченное время
   * Массив отсортирован по убыванию занимаемых командами мест.
   *
   * @return array
   */
  public function getGameResults()
  {
    $res = array();
    foreach ($this->teamStates as $teamState)
    {
      array_push($res, $teamState->getTeamResults());
    }
    usort($res, 'compareTeamPlaces');
    return $res;
  }

  /**
   * Возвращает имя команды-организатора из архива.
   *
   * @return  string
   */
  public function getTeamBackupName()
  {
    if (($this->team_name_backup == null) || ($this->team_name_backup == ''))
    {
      return '(Авторы неизвестны)';
    }
    else
    {
      return $this->team_name_backup.' (Данные из архива)';
    }
  }

  // Action
  
  /**
   * Регистрирует заявку на игру от команды.
   * Если заявка уже подана, то ничего не делает.
   * Если команда уже зарегистрирована - ошибка.
   *
   * @param   Team      $team   Команда, подающая заявку
   * @param   WebUser   $actor  Учетная запись, выполняющая операцию
   * @return  string            True если все в порядке, иначе строковое описание ошибки
   */  
  public function postJoin(Team $team, WebUser $actor)
  {
    if (!$team->canBeManaged($actor) && !$this->canBeManaged($actor))
    {
      return 'Подать заявку от команды может только ее капитан.';
    }
    if (($this->status >= Game::GAME_READY) && !$this->canBeManaged($actor))
    {
      return 'Свободная регистрация на игру '.$this->name.' закрыта, так как игра скоро начнется. Обратитесь к организаторам игры.';
    }
    if ($this->isTeamCandidate($team))
    {
      return true;
    }
    if ($this->isTeamRegistered($team))
    {
      return 'Команда '.$team->name.' уже зарегистрирована на игру '.$this->name.'.';
    }
    if ($this->team_id > 0)
    {
      if ($this->team_id == $team->id)
      {
        return 'Команда '.$team->name.' не может принимать участие в игре '.$this->name.' так как сама организует ее.';
      }
    }

    $newCandidate = new GameCandidate;
    $newCandidate->team_id = $team->id;
    $newCandidate->game_id = $this->id;
    $newCandidate->save();
    return true;
  }

  /**
   * Отменяет поданную заявку на игру.
   * Если заявки нет - ничего не делает.
   *
   * @param   Team      $team   Команда, подавшая заявку
   * @param   WebUser   $actor  Учетная запись, выполняющая операцию
   * @return  string            True если все в порядке, иначе строковое описание ошибки
   */
  public function cancelJoin(Team $team, WebUser $actor)
  {
    if (!$this->canBeManaged($actor) && !$team->canBeManaged($actor))
    {
      return 'Отменить заявку на игру могут только руководитель игры или капитан команды, подавшей заявку.';
    }
    foreach ($this->gameCandidates as $gameCandidate)
    {
      if ($gameCandidate->team_id == $team->id)
      {
        $gameCandidate->delete();
      }
    }
    return true;
  }

  /**
   * Регистрирует команду на игру.
   * Если есть заявка на игру от этой команды, то убирает заявку.
   *
   * @param   Team      $team   Регистрируемая команда
   * @param   WebUser   $actor  Учетная запись, выполняющая операцию
   * @return  string            True если все в порядке, иначе строковое описание ошибки
   */
  public function registerTeam(Team $team, WebUser $actor)
  {
    if (!$this->canBeManaged($actor))
    {
      return 'Зарегистрировать команду на игру может только руководитель игры.';
    }
    if ($this->team_id > 0)
    {
      if ($this->team_id == $team->id)
      {
        return 'Команда '.$team->name.' не может принимать участие в игре '.$this->name.' так как сама организует ее.';
      }
    }
    if ($this->isTeamRegistered($team))
    {
      return true;
    }
    if ($this->isTeamCandidate($team))
    {
      $this->cancelJoin($team, $actor);
    }

    $newTeamStatus = new TeamState;
    $newTeamStatus->team_id = $team->id;
    $newTeamStatus->game_id = $this->id;
    $newTeamStatus->save();
    return true;
  }

  /**
   * Снимает команду с игры.
   *
   * @param   Team      $team   Снимаемая команда
   * @param   WebUser   $actor  Учетная запись, выполняющая операцию
   * @return  string            True если все в порядке, иначе строковое описание ошибки
   */
  public function unregisterTeam(Team $team, WebUser $actor)
  {
    if (!$this->canBeManaged($actor) && !$team->canBeManaged($actor))
    {
      return 'Снять команду с игры может только руководитель игры или капитан команды.';
    }
    foreach ($this->teamStates as $teamState)
    {
      if ($teamState->team_id == $team->id)
      {
        $teamState->delete();
      }
    }
    return true;
  }

  /**
   * Пересчитывает состояние игры (сохраняет в БД).
   * Если возникают ошибки, то возвращает их ассоциативным массивом:
   * - ключ - id команды
   * - данные - сообщение об ошибке
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе массив с ошибкой.
   */
  public function updateState(WebUser $actor)
  {
    if (!Timing::isExpired(time(), Game::MIN_UPDATE_INERVAL, $this->game_last_update)
        || !$this->isActive())
    {
      return true;
    }
    if (!$this->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }

    $errors = array();
    $errCount = 0;

    switch ($this->status)
    {
      //Ожидание начала игры.
      case Game::GAME_STEADY:
        //Если уже можно начинать игру...
        if (time() >= Timing::strToDate($this->start_datetime))
        {
          $this->started_at = time();
          $this->status = Game::GAME_ACTIVE;
        }
        break;

      //Нормальный ход игры.
      case Game::GAME_ACTIVE:
        //Проверим, может быть уже все команды финишировали.
        $allDone = true;
        foreach ($this->teamStates as $teamState)
        {
          if ($teamState->status < TeamState::TEAM_FINISHED)
          {
            $allDone = false;
            break;
          }
        }
        if ($allDone)
        {
          // Все команды финишировали, игру можно остановить.
          $this->stop($actor);
          $this->save();
        }
        //break здесь не нужен, нужно еще пересчитать состояние.
        
      //Игра завершена, но обновления надо продолжать, чтобы все команды
      //корректно закончили игру.
      case Game::GAME_FINISHED:
        foreach ($this->teamStates as $teamState) //??!! Выполнение этого цикла приводит к отмене всех ранее сделанных изменений в данном экземпляре игры.
        {
          if (is_string($res = $teamState->updateState($actor)))
          {
            $errors[$teamState->team_id] = $res;
            $errCount++;
          };
        }
        break;

      default:
        break;
    }

    // Принудительное окончание игры
    if (time() > $this->getGameStopTime())
    {
      $this->stop($actor);
    }

    $this->game_last_update = time();
    $this->save();

    return ($errCount > 0)
        ? $errors
        : true;
  }

  /**
   * Выполняет перезапуск игры.
   * Не учитывает текущее ее состояние, просто удаляет всю информацию.
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function reset(WebUser $actor)
  {
    if (!$this->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }

    /* Следующие две строки приводят к глюкам вроде попытки doctrine вставить
     * строчку без данных. С какого перепугу интересно?
      foreach ($this->teamStates as $teamState)
      { $teamState->reset(true, $actor); } */

    //Делаем через прямые обращения к БД, должно работать каскадное удаление.
    foreach ($this->teamStates as $teamState)
    {
      $query = Doctrine::getTable('TaskState')
              ->createQuery('tts')
              ->delete()
              ->where('team_state_id = ?', array($teamState->id))
              ->execute();

      $teamState->status = TeamState::TEAM_WAIT_GAME;
      $teamState->task_id = null;
      $teamState->started_at = 0;
      $teamState->finished_at = 0;
      $teamState->team_last_update = 0;
    }

    $this->status = Game::GAME_PLANNED;
    $this->started_at = 0;
    $this->finished_at = 0;
    $this->game_last_update = time();
    $this->save();

    return true;
  }

  /**
   * Проводит предыгровую проверку.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   * При наличии ошибок возвращает протокол в виде ассоциативного массива:
   * - ключ teams
   *    - ключ - id команды
   *      - ключ - порядковый номер сообщения в рамках команды
   *        - ключ errLevel - уровень проблемы
   *        - ключ msg - само сообщение
   * - ключ tasks
   *    - ключ - id заданий
   *      - ключи - порядковый номер сообщения в рамках задания
   *        - ключ errLevel - уровень проблемы
   *        - ключ msg - само сообщение
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успешной проверке, иначе протокол проверки.
   */
  public function prepare(WebUser $actor)
  {
    if (!$this->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }
    if ($this->status > Game::GAME_READY)
    {
      return 'Игра '.$this->name.' уже стартовала или завершена.';
    }

    $canStart = true;
    $report = array();

    $line = 0;

    //// Проверка заданий ////
    foreach ($this->tasks as $task)
    {
      // Проверка подсказок
      $hasTips = $task->tips->count() > 0;
      if ($hasTips)
      {
        //Предупреждение о задании с ручным стартом.
        if ($task->manual_start)
        {
          $line++;
          $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_WARN;
          $report['tasks'][$task->id][$line]['msg'] = 'Задание запускается вручную, игра не может быть полностью автоматизирована.';
        }
        
        //Проверка наличия подсказки без задержки, т.е. формулировки
        $hasDefine = false;
        foreach ($task->tips as $tip)
        {
          if ($tip->delay == 0)
          {
            $hasDefine = true;
            break;
          }
        }
        if (!$hasDefine)
        {
          $line++;
          $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_WARN;
          $report['tasks'][$task->id][$line]['msg'] = 'Задание не имеет формулировки (подсказки с нулевой задержкой выдачи).';
        }
        
        //Проверка одновременных подсказок.
        foreach ($task->tips as $tip)
        {
          foreach ($task->tips as $tip2)
          {
            //Если это не одна и та же подсказка
            //и совпадают задержки
            //и обе подсказки не являются дополнениями к кодам
            if (($tip2->id != $tip->id)
                && ($tip2->delay == $tip->delay)
                && (($tip2->answer_id <= 0) && ($tip->answer_id <= 0)))
            {
              $line++;
              $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_WARN;
              $report['tasks'][$task->id][$line]['msg'] = 'Подсказки "'.$tip->name.'" и "'.$tip2->name.'" выдаются одновременно.';
            }
          }
        }
      }
      else
      {
        $line++;
        $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_ERR;
        $report['tasks'][$task->id][$line]['msg'] = 'Задание не имеет ни формулировки, ни подсказок.';
      }

      // Проверка ответов
      $hasAnswers = $task->answers->count() > 0;
      if ($hasAnswers)
      {
        foreach ($task->answers as $answer)
        {
          //Проверка наличия видимых символов в описании.
          if (trim($answer->info) === '')
          {
            $line++;
            $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_WARN;
            $report['tasks'][$task->id][$line]['msg'] = 'Ответ "'.$answer->name.'" имеет невидимое описание.';
          }
          
          //Проверка наличия невидимых символов в значении.
          $parts = explode(' ', $answer->value);
          $count = 0;
          foreach ($parts as $part)
          {
            $count++;
          }
          if ($count > 1)
          {
            $line++;
            $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_ERR;
            $report['tasks'][$task->id][$line]['msg'] = 'Ответ "'.$answer->name.'" не может быть введен (содержит невидимые символы).';
          }
        }
      }
      else
      {
        $line++;
        $report['tasks'][$task->id][$line]['errLevel'] = Game::VERIFY_ERR;
        $report['tasks'][$task->id][$line]['msg'] = 'Задание не имеет ответов.';
      }
    }
    
    //// Проверка команд ////
    foreach ($this->teamStates as $teamState)
    {
      //Проверка доступности игрового времени
      $gameTimeAvailable = Timing::strToDate($this->stop_datetime) - Timing::strToDate($this->start_datetime) - $teamState->start_delay*60;
      if ($gameTimeAvailable < $this->time_per_game*60)
      {
        $line++;
        $report['teams'][$teamState->team_id][$line]['errLevel'] = Game::VERIFY_WARN;
        $report['teams'][$teamState->team_id][$line]['msg'] = 'Команде доступно на игру только '.Timing::intervalToStr($gameTimeAvailable).' из необходимых '.Timing::intervalToStr($this->time_per_game*60).'.';
      }
      //Проверка наличия игроков и капитана.
      if ($teamState->Team->teamPlayers->count() <= 0)
      {
        $line++;
        $report['teams'][$teamState->team_id][$line]['errLevel'] = Game::VERIFY_WARN;
        $report['teams'][$teamState->team_id][$line]['msg'] = 'В команде нет игроков.';
      }
      else
      {
        //Проверка наличия капитана.
        if ($teamState->Team->getLeaders() === false)
        {
          $line++;
          $report['teams'][$teamState->team_id][$line]['errLevel'] = Game::VERIFY_WARN;
          $report['teams'][$teamState->team_id][$line]['msg'] = 'В команде нет капитана.';
        }
        //Проверка вхождения игроков более чем в одну команду.
        foreach ($teamState->Team->teamPlayers as $teamPlayer)
        {
          $player = $teamPlayer->WebUser;
          if ($this->team_id > 0)
          {
            if ($this->Team->isPlayer($player))
            {
              $line++;
              $report['teams'][$teamState->team_id][$line]['errLevel'] = Game::VERIFY_ERR;
              $report['teams'][$teamState->team_id][$line]['msg'] = 'Игрок '.$player->login.' является организатором игры '.$this->name.'.';
              $canStart = false;
            }
          }
          foreach ($this->teamStates as $teamStatus2)
          {
            // Если команды не совпадают,
            // и игрок входит в другую команду...
            // и эта другая команда участвует в рассматриваемой игре
            if (   ($teamStatus2->team_id != $teamState->team_id)
                && ($teamStatus2->Team->isPlayer($player))
                && ($this->isTeamRegistered($teamStatus2->Team)))
            {
              $line++;
              $report['teams'][$teamState->team_id][$line]['errLevel'] = Game::VERIFY_ERR;
              $report['teams'][$teamState->team_id][$line]['msg'] = 'Игрок '.$player->login.' играет еще и за команду '.$teamStatus2->Team->name.'.';
              $canStart = false;
            }
          }
        }
      }
    }

    $this->status = $canStart
        ? Game::GAME_READY
        : Game::GAME_VERIFICATION;
    $this->game_last_update = time();

    return ($line > 0)
        ? $report
        : true;
  }

  /**
   * Запускает игру.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function start(WebUser $actor)
  {
    if (!$this->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }
    if ($this->status < Game::GAME_READY)
    {
      return 'Игра '.$this->name.' еще не прошла предстартовую проверку.';
    }
    if ($this->status > Game::GAME_STEADY)
    {
      return 'Игра '.$this->name.' уже стартовала или завершена.';
    }

    $this->status = Game::GAME_STEADY;
    $this->started_at = 0; //Реальное время старта будет сюда записано при пересчете состояния, когда будет отслежен момент старта.
    $this->finished_at = 0;
    $this->game_last_update = time();

    return true;
  }

  /**
   * Останавливает игру.
   * После остановки требуется еще несколько итераций пересчета состояния,
   * чтобы закрылись все текущие задания и финишировали все команды.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor      Исполнитель
   * @return  mixed                 True при успехе, иначе строка с ошибкой.
   */
  public function stop(WebUser $actor)
  {
    if (!$this->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }
    if ($this->status < Game::GAME_STEADY)
    {
      return 'Игра '.$this->name.' еще не стартовала.';
    }
    if ($this->status > Game::GAME_ACTIVE)
    {
      return 'Игра '.$this->name.' уже завершена.';
    }

    $this->status = Game::GAME_FINISHED;
    $this->finished_at = time();
    $this->game_last_update = time();

    return true;
  }

  /**
   * Переводит игру в архивное состояние.
   * ВНИМАНИЕ: не сохраняет изменения в БД. Save() выполняет вызывающая сторона.
   *
   * @param   WebUser   $actor  Исполнитель
   * @return  mixed             True при успехе, иначе строка с ошибкой.
   */
  public function close(WebUser $actor)
  {
    if (!$this->canBeManaged($actor))
    {
      return Utils::cannotMessage($actor->login, Permission::byId(Permission::GAME_MODER)->description);
    }
    if ($this->status < Game::GAME_FINISHED)
    {
      return 'Игра '.$this->name.' еще не финишировала.';
    }

    $activeTasks = false;
    foreach ($this->teamStates as $teamState)
    {
      if ($teamState->task_state_id > 0)
      {
        $activeTasks = true;
        break;
      }
    }
    if ($activeTasks)
    {
      return 'Игра '.$this->name.' финишировала, но еще не все команды об этом узнали.';
    }

    $this->status = Game::GAME_ARCHIVED;
    $this->game_last_update = time();

    return true;
  }

  /**
   * Назначает исходные значения.
   */
  public function initDefaults()
  {
    if ($this->team_id > 0)
    {
      $this->team_name_backup = $this->Team->name;
    }
  }

}

function compareTeamPlaces($a, $b)
{
  //Команды отличаются по очкам (прямой порядок)
  if ($a['points'] < $b['points'])
  {
    return 1;
  }
  elseif ($a['points'] > $b['points'])
  {
    return -1;
  }
  //Команды отличаются только по времени (обратный порядок)
  elseif ($a['time'] < $b['time'])
  {
    return -1;
  }
  elseif ($a['time'] > $b['time'])
  {
    return 1;
  }
  //Команды ничем не отличаются
  else
  {
    return 0;
  }
}
