<div>
  <h1><img src="/images/favicon.png" alt="[BE]">&nbsp;Beaver's&nbsp;Engine</h1>
  <h2><span style="font-size: small">...он же</span> Йа Движко!</h2>
  <h4 style="border: none">Система для проведения интерактивных игр</h4>
</div>

<?php if (!$sf_user->isAuthenticated()): ?>
<div class="hr">
  <p>
    Для доступа ко всем функциям Вам нужно <?php echo link_to('войти', 'auth/login')?>.
  </p>
  <p>
    Если Вы здесь впервые, то сначала <?php echo link_to('зарегистрируйтесь', 'auth/register')?>.
  </p>
</div>
<h3>Новости</h3>
<?php
include_component('blog', 'blog', array(
    'id' => 1,
    'page' => $_page,
    'expandedPostId' => $_expandPostId,
    'backUrl' => 'home/index'
))
?>

<?php else: ?>
<h3>Новости</h3>
<?php
$sessionWebUser = $sf_user->getSessionWebUser()->getRawValue();
$sessionIsBlogModer = $sessionWebUser->can(Permission::BLOG_MODER, 1);
include_component('blog', 'blog', array(
    'id' => 1,
    'page' => $_page,
    'expandedPostId' => $_expandPostId,
    'webUserId' => $sessionWebUser->id,
    'backUrl' => 'home/index',
    'readOnly' => false,
    'editorModule' => 'moderation',
    'canPost' => $sessionIsBlogModer,
    'canComment' => true,
    'canEditSelf' => true,
    'canEditAny' => $sessionIsBlogModer,
    'canDeleteSelf' => true,
    'canDeleteAny' => $sessionIsBlogModer
))
?>

<?php endif; ?>
