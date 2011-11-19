<?php
render_breadcombs(array(
    link_to('Модерирование', 'moderation/show'),
    'Регионы',
    ))
?>

<h2>Все регионы</h2>

<?php echo link_to('Создать новый регион', 'region/new'); ?>
<h3></h3>
<ul>
  <?php foreach ($_regions as $region): ?>
  <li>
    <?php
    echo decorate_span('danger', link_to('Удалить', 'region/delete?id='.$region->id, array('method' => 'delete', 'confirm' => 'Вы правда хотите удалить регион '.$region->name.' ?')));
    echo ' '.decorate_span('warn', link_to('Править', 'region/edit?id='.$region->id));
    echo ' '.$region->name;
    ?>
  </li>
  <?php endforeach; ?>
</ul>