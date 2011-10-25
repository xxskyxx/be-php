<?php
/**
 * Входные аргументы:
 * $_blogContext  Контекст блога
 */
?>
<div class="blogPagesIndex">
  <?php
  echo 'Страницы:';
  for ($page = 1; $page <= $_blogContext->pageCount; $page++)
  {
    echo ($page != $_blogContext->page)
      ? decorate_div('item', link_to($page, $_blogContext->backUrl.'?page='.$page))
      : decorate_div('currentItem', $page);
  }
  ?>
</div>
