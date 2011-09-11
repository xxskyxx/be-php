<div>
  <div style="float: right; text-align: right; vertical-align: top; min-height: 1.5em">
    <?php
    if ($sf_user->isAuthenticated())
    {
      echo '('.link_to($sf_user->getAttribute('login'), 'webUser/show?id='.$sf_user->getAttribute('id')).')';
      echo ' '.link_to('Выйти', 'auth/logout');
    }
    else
    {
      echo link_to('Зарегистрироваться', 'auth/register');
      echo ' '.link_to('Войти', 'auth/login');
    }
    ?>
  </div>

  <div style="min-height: 1.5em">
    <?php include ('customization/header.php') ?>
  </div>
  
</div>