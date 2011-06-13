<div class="homeHeader">
  <h1><img src="/images/favicon.png" alt="[BE]">&nbsp;Beaver's&nbsp;Engine</h1>
  <h2><span style="font-size: small">...он же</span> Йа Движко!</h2>
  <h3>Система для проведения интерактивных игр</h3>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<div>
  Для дальнейшей работы вам нужно <a href="<?php echo url_for('auth/login'); ?>">войти</a>.
</div>
<div>
  Если у вас еще нет учетной записи здесь, то сначала <a href="<?php echo url_for('auth/register'); ?>">зарегистрируйтесь</a>.
</div>
<?php else: ?>
  Добро пожаловать!
<?php endif; ?>