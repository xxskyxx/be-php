<?php

class homeActions extends MyActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $this->_userAuthenticated = $this->session->isAuthenticated();
    $announce_interval = SystemSettings::getInstance()->games_announce_interval;
    $this->_currentRegion = Region::byIdSafe($this->session->getAttribute('region_id'));
    if ($this->session->isAuthenticated())
    {
      $this->_games = Game::getGamesForAnnounce($announce_interval, $this->_currentRegion);
    }
    else
    {
      $this->_games = Game::getGamesForAnnounce($announce_interval, Region::byId(Region::DEFAULT_REGION));
    }
  }

}
