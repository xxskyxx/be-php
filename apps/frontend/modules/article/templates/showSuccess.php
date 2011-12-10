<?php
  $path = get_path_to_article($_article->getRawValue());
  if ( ! $path)
  {
    render_breadcombs(array(
        link_to('Статьи', 'article/index'),
        $_article->name
    ));
  }
  else
  {
    $links = array();
    $links = array_merge($links, array(link_to('Статьи', 'article/index')));
    $links = array_merge($links, $path);
    $links = array_merge($links, array($_article->name));
    render_breadcombs($links);
  }  
?>

<h3 class="inline"><?php echo $_article->name ?></h3>
<?php
echo ($_isModer) ? '(Id#'.$_article->id.')' : '';
echo ' &copy;&nbsp;'.$_article->getAuthorNameSafe();
echo ', '.Timing::dateToStr($_article->created_at);
?>
  
<?php if ($_canEdit): ?>
<p>
  <span class="safeAction"><?php echo link_to('Редактировать статью', 'article/edit?id='.$_article->id) ?></span>
  <span class="dangerAction"><?php echo link_to('Удалить статью', 'article/delete?id='.$_article->id, array('method' => 'post', 'confirm' => 'Вы уверены, что хотите удалить статью?')) ?></span>
</p>
<?php endif ?>
<div class="hr"></div>

<div>
  <?php echo Utils::decodeBB($_article->text); ?>
</div>