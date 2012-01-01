<?php

class permissionsFilter extends sfFilter
{

  public function execute($filterChain)
  {
    if ($this->isFirstCall())
    {
      $request = $this->getContext()->getRequest();
      $moduleName = strtolower($request->getParameter('module'));
      //На эти модули у всех всегда есть право
      if ( ! (($moduleName === 'home')
              || ($moduleName === 'auth')
              || ($moduleName === 'article')
              || ($moduleName === 'region')) )
      {
        $session = $this->getContext()->getUser();
        $controller = $this->getContext()->getController();

        if ( ! $session->isAuthenticated())
        {
          $session->setFlash('error', 'Вы не авторизованы. Авторизуйтесь и повторите операцию.');
          $session->setAttribute('redirected', 1);
          $controller->redirect('auth/login');
          exit; //Без этого действие все-равно выполнится.
        }
      }
    }
    //Запускаем следующий в цепи фильтр.
    $filterChain->execute();
  }

}

?>