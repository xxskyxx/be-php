<div>
  <div style="float: right; text-align: right; vertical-align: top; min-height: 1.5em">
    <?php
    if ($sf_user->isAuthenticated())
    {
      //$backLink = 'home/index';
      $backLink = $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
      echo '(';
      echo link_to($sf_user->getAttribute('login'), 'webUser/show?id='.$sf_user->getAttribute('id'));
      echo '&nbsp;@&nbsp;';
      echo link_to(Region::byId($sf_user->getAttribute('region_id'))->name, 'region/setCurrent?returl='.Utils::encodeSafeUrl($backLink));
      echo ') '.link_to('Выйти', 'auth/logout');
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