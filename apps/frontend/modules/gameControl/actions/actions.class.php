<?php

/**
 * Контроллер управления игрой.
 */
class gameControlActions extends MyActions
{

  public function executePilot(sfRequest $request)
  {
    $this->checkAndSetGame($request);
    $this->errorRedirectUnless(
        ($this->_game->canBeObserved($this->sessionWebUser)),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру')
    );
    $this->prefetchAll($request);
    $this->_isManager = $this->_game->canBeManaged($this->sessionWebUser);
  }

  public function executeSturman(sfRequest $request)
  {
    $this->checkAndSetGame($request);
    $this->errorRedirectUnless(
        ($this->_game->canBeObserved($this->sessionWebUser)),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру')
    );
    $this->prefetchAll($request);
    $this->_isManager = $this->_game->canBeManaged($this->sessionWebUser);
  }

  public function executeEngineer(sfRequest $request)
  {
    $this->checkAndSetGame($request);
    $this->errorRedirectUnless(
        ($this->_game->canBeObserved($this->sessionWebUser)),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру')
    );
    $this->prefetchAll($request);
    $this->_isManager = $this->_game->canBeManaged($this->sessionWebUser);
  }

  public function executeStuart(sfRequest $request)
  {
    $this->checkAndSetGame($request);
    $this->errorRedirectUnless(
        ($this->_game->canBeObserved($this->sessionWebUser)),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру')
    );
    $this->_isManager = $this->_game->canBeManaged($this->sessionWebUser);
  }

  public function executeVerify(sfWebRequest $request)
  {
    $this->checkPostAndCsrf($request);
    $this->checkAndSetGame($request);
    if (is_string($res = $this->_game->prepare($this->sessionWebUser)))
    {
      $this->errorRedirect('Выполнить предстартовую проверку игры '.$this->_game->name.' не удалось: '.$res);
    }
    if (is_array($res))
    {
      $this->report = $res;
      $this->_game->save();
    }
    else
    {
      $this->_game->save();
      $this->successRedirect('Игра '.$this->_game->name.' прошла предстартовую проверку без ошибок и замечаний.');
    }
  }
  
  public function executeReport(sfWebRequest $request)
  {
    $this->checkAndSetGame($request);
    $this->errorRedirectUnless(
        ($this->_game->canBeObserved($this->sessionWebUser))
        || ($this->_game->status >= Game::GAME_ARCHIVED),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру')
    );
  }
  
  //// Game control

  public function executeReset(sfWebRequest $request)
  {
    $this->checkPostAndCsrf($request);
    $this->checkAndSetGame($request);
    if (is_string($res = $this->_game->reset($this->sessionWebUser)))
    {
      $this->errorRedirect('Перезапустить игру '.$this->_game->name.' не удалось: '.$res);
    }
    $this->_game->save();
    $this->successRedirect('Игра '.$this->_game->name.' успешно перезапущена.');
  }
  
  public function executeStart(sfWebRequest $request)
  {
    $this->checkPostAndCsrf($request);
    $this->checkAndSetGame($request);
    if (is_string($res = $this->_game->start($this->sessionWebUser)))
    {
      $this->errorRedirect('Запустить игру'.$this->_game->name.' не удалось: '.$res);
    }
    $this->_game->Save();
    $this->successRedirect('Игра '.$this->_game->name.' успешно запущена.');
  }  
  
  public function executeStop(sfWebRequest $request)
  {
    $this->checkPostAndCsrf($request);
    $this->checkAndSetGame($request);
    if (is_string($res = $this->_game->stop($this->sessionWebUser)))
    {
      $this->errorRedirect('Остановить игру'.$this->_game->name.' не удалось: '.$res);
    }
    $this->_game->save();
    $this->successRedirect('Игра '.$this->_game->name.' успешно остановлена.');
  }  
  
  public function executeClose(sfWebRequest $request)
  {
    $this->checkPostAndCsrf($request);
    $this->checkAndSetGame($request);
    if (is_string($res = $this->_game->close($this->sessionWebUser)))
    {
      $this->errorRedirect('Сдать игру'.$this->_game->name.' в архив не удалось: '.$res);
    }
    $this->_game->save();
    $this->successRedirect('Игра '.$this->_game->name.' успешно сдана в архив.');
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->checkPostAndCsrf($request);
    $this->checkAndSetGame($request);
    if (is_string($res = $this->_game->updateState($this->sessionWebUser)))
    {
      $this->errorRedirect('Пересчитать состояние игры '.$this->_game->name.' не удалось: '.$res);
    }
    $this->successRedirect('Состояние игры '.$this->_game->name.' успешно пересчитано в '.Timing::timeToStr(time()).'.');
  }
  
  public function executeAutoUpdate(sfWebRequest $request)
  {
    $this->checkAndSetGame($request);
    $this->errorRedirectUnless($this->_game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'обновлять состояние игры'));
    if (is_bool($res = $this->_game->updateState($this->sessionWebUser)))
    {
      $this->_result = 'Ok';
    }
    else
    {
      $this->_result = var_dump($res); //TODO: Переделать во что-то более дружественное.
    }
  }
  
  public function executeSetNext(sfWebRequest $request)
  {
    $this->forward404Unless($this->_teamState = TeamState::byId($request->getParameter('teamState')), 'Состояние команды не найдено.');
    $taskId = $request->getParameter('taskId', -1);
    $this->_retUrl = $this->retUrlRaw;

    if ($taskId > -1)
    {
      // Диалог выполнен, задание выбрано
      if ($taskId == 0)
      {
        if (is_string($res = $this->_teamState->setNextTask(null, $this->sessionWebUser)))
        {
          $this->errorRedirect('Отменить команде '.$this->_teamState->Team->name.' следующее задание не удалось: '.$res);
        }
        $this->_teamState->save();
        $this->successRedirect('Команде '.$this->_teamState->Team->name.' отменено следующее задание.');
      }
      else
      {
        $this->forward404Unless($task = Task::byId($taskId), 'Задание не найдено.');
        if (is_string($res = $this->_teamState->setNextTask($task, $this->sessionWebUser)))
        {
          $this->errorRedirect('Назначить команде '.$this->_teamState->Team->name.' следующее задание не удалось: '.$res);
        }
        $this->_teamState->save();
        $this->successRedirect('Команде '.$this->_teamState->Team->name.' успешно назначено следующее задание.');
      }
    }
    else
    {
      // Диалог только что открыт, надо сформировать список для выбора.
      $this->_availableTasks = $this->_teamState->getAvailableTasks(false);
      if ( ! $this->_availableTasks)
      {
        $this->errorRedirect('У команды '.$this->_teamState->Team->name.' нет доступных для выдачи заданий.');
      }
    }
  }  
  
  //// Self
  
  protected function prefetchAll(sfRequest $request)
  {
    $game = $this->_game;

    $teamStates = Doctrine::getTable('TeamState')
        ->createQuery('ts')
        ->select()
            ->innerJoin('ts.Game')
            ->innerJoin('ts.Team')
            ->leftJoin('ts.taskStates')
        ->where('game_id = ?', $game->id)
        ->orderBy('ts.Team.name, ts.taskStates.given_at')
        ->execute();

    $teamStatesIds = DCTools::idsToArray($teamStates);
    $taskStates = Doctrine::getTable('TaskState')
        ->createQuery('ts')
        ->select()
            ->innerJoin('ts.TeamState')
            ->innerJoin('ts.Task')
            ->leftJoin('ts.usedTips')
            ->leftJoin('ts.postedAnswers')
        ->whereIn('team_state_id', $teamStatesIds)
        ->orderBy('ts.given_at')
        ->execute();

    $taskStatesIds = DCTools::idsToArray($taskStates);
    $usedTips = Doctrine::getTable('UsedTip')
        ->createQuery('ut')
        ->select()
            ->innerJoin('ut.TaskState')
            ->innerJoin('ut.Tip')
        ->whereIn('task_state_id', $taskStatesIds)
        ->orderBy('ut.used_since')
        ->execute();

    $tasks = Doctrine::getTable('Task')
        ->createQuery('t')
        ->select()
            ->innerJoin('t.Game')
            ->leftJoin('t.taskStates')
        ->where('game_id = ?', $game->id)
        ->orderBy('t.name')
        ->execute();


    /* Формат $currentTaskStatesIndex:
     * ключ - id состояния команды
     * значение - текущее состояние задания
     */
    $currentTaskStatesIndex = array();
    foreach ($teamStates as $teamState)
    {
      $currentTaskState = $teamState->getCurrentTaskState();
      $currentTaskStatesIndex[$teamState->id] = $currentTaskState
          ? DCTools::recordById($taskStates, $currentTaskState->id)
          : false;
    }

    $this->_teamStates = $teamStates;
    $this->_taskStates = $taskStates;
    $this->_usedTips = $usedTips;
    $this->_tasks = $tasks;

    $this->_currentTaskStatesIndex = $currentTaskStatesIndex;
  }

  protected function checkPostAndCsrf(sfRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $request->checkCSRFProtection();
  }

  protected function checkAndSetGame(sfRequest $request)
  {
    $this->forward404Unless($this->_game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
  }
  
}

?>
