<?php

class antiDosFilter extends sfFilter
{
  
  public function execute($filterChain)
  {
    if ($this->isFirstCall())
    {
      $session = $this->getContext()->getUser();
      $timeNow = time();
      $lastAccess = $session->getAttribute('lastAccess', 0);
      $session->setAttribute('lastAccess', $timeNow);
      $redirected = $session->getAttribute('redirected', 0);
      if ($redirected > 0)
      {
        $session->setAttribute('redirected', 0);
      }
      
      if (($lastAccess == $timeNow) //т.е. разница между запросами меньше секунды.
          && ($redirected == 0)) 
      {
        include('../apps/frontend/config/error/unavailable.php');
        exit;
      }
    }
    $filterChain->execute();
  }
  
}
?>
