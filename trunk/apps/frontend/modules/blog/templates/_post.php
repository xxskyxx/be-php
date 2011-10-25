<?php if (!$_post || !$_blogContext): ?>
<p>
  <div class="danger">Сообщение не найдено или неверный контекст блога</div>
</p>
<?php else: ?>
<?php   $retUrlRaw = Utils::encodeSafeUrl($_blogContext->backUrl.'?page='.$_blogContext->page.'&expandPostId='.$_blogContext->expandedPostId); ?>
<div class="blogPost">
  
  <div class="itemControls">
    <?php
    echo ($_blogContext->canEditAny || ($_blogContext->canEditSelf && ($_post->web_user_id == $_blogContext->webUserId)))
        ? decorate_span('warnAction', link_to('Править', $_blogContext->editorModule.'/editPost?id='.$_post->id.'&returl='.$retUrlRaw)).' ' : '';
    echo ($_blogContext->canDeleteAny || ($_blogContext->canDeleteSelf && ($_post->web_user_id == $_blogContext->webUserId)))
        ? decorate_span('dangerAction', link_to('Удалить', $_blogContext->editorModule.'/deletePost?id='.$_post->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Удалить запись?'))).' ' : '';
    echo Timing::dateToStr($_post->create_time).' ';
    if ($_blogContext->readOnly)
    {
      echo decorate_span('info', $_post->WebUser->login);
    }
    else
    {
      echo decorate_span('safeAction', link_to($_post->WebUser->login, 'webUser/show?id='.$_post->web_user_id, array('target' => 'new')));
    }
    ?>
  </div>
  
  <div class="text">
    <?php echo Utils::decodeBB($_post->text); ?>
  </div>
  
  <div>
    <?php if ($_comments->count() > 0): ?>
    
    <?php   if ($_collapsed): ?>
    <div class="blogComment">
      <div class="text">
        <?php echo link_to('Показать все комментарии', $_blogContext->backUrl.'?page='.$_blogContext->page.'&expandPostId='.$_post->id); ?>
      </div>
    </div>
    <?php   endif ?>
    
    <?php
    foreach ($_comments as $comment)
    {
      include_partial('blog/comment', array(
          '_comment' => $comment,
          '_blogContext' => $_blogContext
      ));
    }
    ?>
    
    <?php endif ?>
    
    <?php if ($_blogContext->canComment): ?>
    <div class="blogComment">
      <div class="text">
        <span class="safeAction"><?php echo link_to('Добавить комментарий', $_blogContext->editorModule.'/newComment?postId='.$_post->id.'&returl='.$retUrlRaw); ?></span>
      </div>
    </div>
    <?php endif ?>
  </div>
</div>
<?php endif ?>