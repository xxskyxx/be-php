<?php if (!$_blog || !$_blogContext): ?>
<p>
  <div class="danger">Блог не найден или неверный контекст блога</div>
</p>
<?php else: ?>
<?php   if ($_blogContext->canPost): ?>
<p>
  <span class="safeAction"><?php echo link_to('Новое сообщение', $_blogContext->editorModule.'/newPost?blogId='.$_blog->id); ?></span>
</p>
<?php   endif ?>

<?php   if ($_posts->count() == 0): ?>
<p>
  Здесь пока нет сообщений.
</p>
<?php   else: ?>
<div class="blogPages">
  <?php include_partial('blog/pagesIndex', array('_blogContext' => $_blogContext)); ?>
  <div>
    <?php
      echo ($_blogContext->page > 1)
          ? link_to('Предыдущие сообщения', $_blogContext->backUrl.'?page='.($_blogContext->page-1))
          : '&nbsp;';
    ?>
  </div>    
</div>
<div>
  <?php
  foreach ($_posts as $post)
  {
    include_component('blog', 'post', array(
        'post' => $post,
        'blogContext' => $_blogContext
    ));
  }
  ?>
</div>
<div class="blogPages">
  <?php include_partial('blog/pagesIndex', array('_blogContext' => $_blogContext)); ?>
  <div>
    <?php
      echo ($_blogContext->page < $_blogContext->pageCount)
          ? link_to('Следующие сообщения', $_blogContext->backUrl.'?page='.($_blogContext->page+1))
          : '&nbsp;';
    ?>
  </div>    
</div>
<?php   endif ?>

<?php endif ?>
