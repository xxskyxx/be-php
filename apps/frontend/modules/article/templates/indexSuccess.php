<?php render_breadcombs(array('Статьи')) ?>

<h2>Cтатьи</h2>

<?php if ($sf_user->isAuthenticated()): ?>
<p>
  <?php echo link_to('Написать статью', 'article/new') ?>
</p>
<?php endif ?>

<h3>Все</h3>
<?php if ($_articles->count() > 0): ?>
<ul>
<?php   foreach ($_articles as $article): ?>
  <li>
    <?php
    echo link_to($article->name, 'article/show?id='.$article->id);
    echo ($article->web_user_id == $_sessionWebUserId)
        ? decorate_span('info', ' -&nbsp;ваша&nbsp;статья')
        : '';   
    if ($article->path !== "")
    {
      echo ', (';
      $links = get_path_to_article($article->getRawValue());
      foreach ($links as $link)
      {
        echo '\\'.$link;
      }
      echo ')';
    }
    ?>
  </li>
<?php   endforeach; ?>
</ul>
<?php else: ?>
Пока что не написано ни одной статьи.
<?php endif; ?>
