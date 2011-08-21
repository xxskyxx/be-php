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
      if ( ! (($moduleName === 'home') || ($moduleName === 'auth')) )
      {
        $session = $this->getContext()->getUser();
        $controller = $this->getContext()->getController();

        if (!$session->isAuthenticated())
        {
          $session->setFlash('error', 'Вы не авторизованы. Авторизуйтесь и повторите операцию.');
          $controller->redirect('auth/login');
          exit; //Без этого действие все-равно выполнится.
        }

        //Возможна ситуация, когда пользователь авторизован, но уже удален из БД, проверим
        $sessionWebUser = WebUser::byId($session->getAttribute('id', 0));
        if (!$sessionWebUser)
        {
          $session->setFlash('error', 'Ваша учетная запись не найдена. Авторизуйтесь и повторите операцию.');
          $controller->redirect('auth/logout');
          exit; //Без этого действие все-равно выполнится.
        }

        //Пользователь гарантированно есть и его id известен.
        if (!$sessionWebUser->is_enabled)
        {
          $session->setFlash('error', 'Ваша учетная запись отключена, вы не можете делать что-либо.');
          $controller->redirect('auth/activateManual');
          exit; //Без этого действие все-равно выполнится.
        }
      }
    }
    //Запускаем следующий в цепи фильтр.
    $filterChain->execute();
  }

}

?>