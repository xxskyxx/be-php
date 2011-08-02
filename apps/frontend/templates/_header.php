<div>
  <div style="display: inline-block; text-align: left; width: 49%">
    <div class="middleVertical">
      <img src="/images/favicon.png" alt="[BE]" onClick="document.location='/home/index'" />
    </div>
    <div class="middleVertical"><span style="font-weight:bold">Beaver's&nbsp;Engine</span></div>  
  </div>
  <div style="display: inline-block; text-align: right; width: 50%">
    <?php
    if ($sf_user->isAuthenticated())
    {
      echo '('.link_to($sf_user->getAttribute('login'), 'webUser/show?id='.$sf_user->getAttribute('id')).')';
      echo ' '.link_to('Выйти', 'auth/logout');
    }
    else
    {
      echo link_to('Регистрация', 'auth/register');
      echo ' |&nbsp;'.link_to('Вход', 'auth/login');
    }
    ?>
  </div>
</div>