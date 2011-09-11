<?php if (!$sf_user->isAuthenticated()): ?>
<?php   include('customization/homeNonAuth.php') ?>
<?php else: ?>
<?php   include('customization/homeAuth.php') ?>
<?php endif; ?>

<div>
  <?php include('customization/homeCommon.php') ?>
</div>

<h3>Новости</h3>
<?php if (!$sf_user->isAuthenticated()): ?>
<?php
include_component('blog', 'blog', array(
    'id' => 1,
    'page' => $_page,
    'expandedPostId' => $_expandPostId,
    'backUrl' => 'home/index'
))
?>
<?php else: ?>
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
