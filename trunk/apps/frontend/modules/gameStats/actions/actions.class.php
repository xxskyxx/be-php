<?php

/**
 * Контроллер отображения состояния игры.
 */
class gameStatsActions extends MyActions
{

  public function preExecute()
  {
    parent::preExecute();
  }

  public function executeStatus(sfWebRequest $request)
  {
    $this->decodeArgs($request, false);
    $this->seat = $request->getParameter('seat', 'sturman');
    $this->errorRedirectUnless($this->game->canBeObserved($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру'));
  }

  public function executeReset(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->reset($this->sessionWebUser)))
    {
      $this->errorRedirect('Перезапустить игру'.$game->name.' не удалось: '.$res);
    }
    $this->game->save();
    $this->successRedirect('Игра '.$this->game->name.' успешно перезапущена.');
  }

  public function executeVerify(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->prepare($this->sessionWebUser)))
    {
      $this->errorRedirect('Выполнить предстартовую проверку игры '.$game->name.' не удалось: '.$res);
    }
    if (is_array($res))
    {
      $this->report = $res;
      $this->game->save();
    }
    else
    {
      $this->game->save();
      $this->successRedirect('Игра '.$this->game->name.' прошла предстартовую проверку без ошибок и замечаний.');
    }
  }

  public function executeStart(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->start($this->sessionWebUser)))
    {
      $this->errorRedirect('Запустить игру'.$game->name.' не удалось: '.$res);
    }
    $this->game->Save();
    $this->successRedirect('Игра '.$this->game->name.' успешно запущена.');
  }

  public function executeStop(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->stop($this->sessionWebUser)))
    {
      $this->errorRedirect('Остановить игру'.$game->name.' не удалось: '.$res);
    }
    $this->game->save();
    $this->successRedirect('Игра '.$this->game->name.' успешно остановлена.');
  }

  public function executeClose(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->close($this->sessionWebUser)))
    {
      $this->errorRedirect('Сдать игру'.$game->name.' в архив не удалось: '.$res);
    }
    $this->game->save();
    $this->successRedirect('Игра '.$this->game->name.' успешно сдана в архив.');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->decodeArgs($request);
    if (is_string($res = $this->game->updateState($this->sessionWebUser)))
    {
      $this->errorRedirect('Пересчитать состояние игры '.$game->name.' не удалось: '.$res);
    }
    $this->successRedirect('Состояние игры '.$this->game->name.' успешно пересчитано в '.Timing::timeToStr(time()).'.');
  }

  public function executeSetNext(sfWebRequest $request)
  {
    $this->forward404Unless($this->teamState = TeamState::byId($request->getParameter('teamState')), 'Состояние команды не найдено.');
    $taskId = $request->getParameter('taskId', -1);
    $this->retUrl = $this->retUrlRaw;

    if ($taskId > -1)
    {
      // Диалог выполнен, задание выбрано
      // Если задание равно 0, то это значит, что надо отменить назначение следующего задания.
      if ($taskId == 0)
      {
        if (is_string($res = $this->teamState->setNextTask(null, $this->sessionWebUser)))
        {
          $this->errorRedirect('Отменить команде '.$this->teamState->Team->name.' следующее задание не удалось: '.$res);
        }
        $this->teamState->save();
        $this->successRedirect('Команде '.$this->teamState->Team->name.' отменено следующее задание.');
      }
      else
      {
        $this->forward404Unless($task = Task::byId($taskId), 'Задание не найдено.');
        if (is_string($res = $this->teamState->setNextTask($task, $this->sessionWebUser)))
        {
          $this->errorRedirect('Назначить команде '.$this->teamState->Team->name.' следующее задание не удалось: '.$res);
        }
        $this->teamState->save();
        $this->successRedirect('Команде '.$this->teamState->Team->name.' успешно назначено следующее задание.');
      }
    }
    else
    {
      // Диалог только что открыт, надо сформировать список для выбора.
      $this->tasks = $this->teamState->getAvailableTasks();
      if (!$this->tasks)
      {
        $this->errorRedirect('У команды '.$this->teamState->Team->name.' нет доступных для выдачи заданий.');
      }
    }
  }

  public function executeReport(sfWebRequest $request)
  {
    $this->decodeArgs($request, false);
    $this->errorRedirectUnless(
        ($this->game->canBeObserved($this->sessionWebUser))
        || ($this->game->status >= Game::GAME_ARCHIVED),
        Utils::cannotMessage($this->sessionWebUser->login, 'просматривать игру')
    );
  }

  public function executeAutoUpdate(sfWebRequest $request)
  {
    $this->decodeArgs($request, false);
    $this->errorRedirectUnless($this->game->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'обновлять состояние игры'));
    if (is_bool($res = $this->game->updateState($this->sessionWebUser)))
    {
      $this->result = 'Ok';
    }
    else
    {
      $this->result = var_dump($res); //TODO: Переделать во что-то более дружественное.
    }
  }

  public function decodeArgs(sfWebRequest $request, $checkPostAndCSRF = true)
  {
    if ($checkPostAndCSRF)
    {
      $this->forward404Unless($request->isMethod(sfRequest::POST));
      $request->checkCSRFProtection();
    }
    $this->forward404Unless($this->game = Game::byId($request->getParameter('id')), 'Игра не найдена.');
  }

}

?>
