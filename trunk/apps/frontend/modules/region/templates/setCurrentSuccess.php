
<h2>Выбор текущего региона</h2>

<span class="safeAction"><?php echo link_to('Вернуться', $_retUrlDecoded); ?></span>
<div class="hr"></div>

<ul>
  <?php if ($_selfRegionId != Region::DEFAULT_REGION): ?>
  <li>
    <span class="info"><?php echo link_to('Свой регион (из анкеты)', 'region/setCurrent?id='.$_selfRegionId.'&returl='.$_retUrlRaw, array('method' => 'post')); ?></span>
  </li>
  <?php endif ?>
  <?php foreach($_regions as $region): ?>
  <li>
    <?php
    $html = link_to($region->name, 'region/setCurrent?id='.$region->id.'&returl='.$_retUrlRaw, array('method' => 'post'));
    if ($region->id == Region::DEFAULT_REGION)
    {
      echo decorate_span('info', $html);
    }
    else
    {
      echo $html;
    }
    ?>
  </li>
  <?php endforeach; ?> 
</ul>

<p>
  <span class="info">Если нужного Вам региона нет в списке,</span> <?php echo mail_to(SystemSettings::getInstance()->contact_email_addr, 'напишите администраторам') ?>.
</p>
