<div>
  <h1><img src="/images/favicon.png" alt="[BE]">&nbsp;Beaver's&nbsp;Engine</h1>
  <h2><span style="font-size: small">...он же</span> Йа Движко!</h2>
  <h3 style="border: none">Система для проведения интерактивных игр</h3>
</div>

<div class="hr">
  <?php if (!$sf_user->isAuthenticated()): ?>
  <p>
    Для дальнейшей работы вам нужно <a href="<?php echo url_for('auth/login'); ?>">войти</a>.
  </p>
  <p>
    Если у вас еще нет учетной записи здесь, то сначала <a href="<?php echo url_for('auth/register'); ?>">зарегистрируйтесь</a>.
  </p>
  <?php else: ?>
  <p>
    Добро пожаловать!
  </p>
  <?php endif; ?>
</div>