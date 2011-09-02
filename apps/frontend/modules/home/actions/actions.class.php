<?php

class homeActions extends MyActions
{

  public function executeIndex(sfWebRequest $request)
  {
    $this->_page = $request->getParameter('page', 0);
    $this->_expandPostId = $request->getParameter('expandPostId', 0);
  }

}
