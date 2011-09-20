<?php

class staticActions extends sfActions
{

  public function executeView(sfWebRequest $request)
  {
    $urlRaw = $request->getParameter('pageUrl');
    $url = 'statical/'.Utils::decodeSafeUrl($urlRaw);
    if ( ! file_exists($url))
    {
      $this->redirect404();
    }
    $this->_url = $url;
  }

}

?>
