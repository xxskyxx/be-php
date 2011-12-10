<?php render_breadcombs(array(link_to('Статьи', 'article/index'))) ?>

<h2>404 ...Not found</h2>
<h3>Статья "<?php echo $_articleName ?>"</h3>
<p>
  Извините, но такую статью найти не удалось.
</p>
<p>
  Вы можете попробовать найти ее вручную в <?php echo link_to('общем списке статей', 'article/index') ?>.
</p>

<?php if ($_children->count() > 0):?>
<h3>Ссылающиеся статьи</h3>
<p>
  Ниже приведен список статей, для которых статья "<?php echo $_articleName ?>" объявлена как родительская:
</p>
<ul>
<?php  foreach ($_children as $article): ?>
  <li><?php echo link_to_article($article->getRawValue()) ?></li>
<?php  endforeach ?>
</ul>
<?php endif ?>
