<?php
/**
 * Входные аргументы:
 * @param   $_comment       Отображаемый комментарий
 * @param   $_blogContext   Контекст блога
 */
?>
<div class="blogComment">
  <div class="itemControls">
    <?php
    $retUrlRaw = Utils::encodeSafeUrl($_blogContext->backUrl.'?page='.$_blogContext->page.'&expandPostId='.$_blogContext->expandedPostId);
    echo ($_blogContext->canEditAny
          || ($_blogContext->canEditSelf && ($_comment->web_user_id == $_blogContext->webUserId)))
        ? decorate_span('warnAction', link_to('Править', $_blogContext->editorModule.'/editComment?id='.$_comment->id.'&returl='.$retUrlRaw)).' ' : '';
    echo ($_blogContext->canDeleteAny
         || ($_blogContext->canDeleteSelf && ($_comment->web_user_id == $_blogContext->webUserId)))
        ? decorate_span('dangerAction', link_to('Удалить', $_blogContext->editorModule.'/deleteComment?id='.$_comment->id.'&returl='.$retUrlRaw, array('method' => 'delete', 'confirm' => 'Удалить комментарий?'))).' ' : '';
    echo Timing::dateToStr($_comment->create_time).' ';
    echo decorate_span('safeAction', link_to_if(!$_blogContext->readOnly, $_comment->WebUser->login, 'webUser/show?id='.$_comment->web_user_id, array('target' => 'new')));
    ?>
  </div>

  <div class="text">
    <?php echo Utils::decodeBB($_comment->text); ?>
  </div>
</div>