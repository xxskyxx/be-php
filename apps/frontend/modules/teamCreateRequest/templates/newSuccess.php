<?php render_breadcombs(array(link_to('Команды', 'team/index'))) ?>
<h2>Подача заявки на создание команды</h2>
<?php if (SystemSettings::getInstance()->fast_team_create): ?>
<div class="spaceAfter">
  <div class="info">
    <div>Сейчас разрешено создание команд без модерирования:</div>
    <div>после подачи заявки подтвердите ее самостоятельно на странице со списком команд.</div>
  </div>
</div>
<?php endif ?>
<?php include_partial('form', array('form' => $form)) ?>
