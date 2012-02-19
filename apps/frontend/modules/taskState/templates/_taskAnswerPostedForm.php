<?php
/**
 * Входные аргументы:
 * - $form - привязанная к "полуфабрикату" ответа форма.
 * - $returl - адрес обратного перехода (шифрованный)
 */
?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<form action="
  <?php
  /* TODO: приходится отправлять оба параметра в CGI-формате, так как...
   * при попытке url_for от всей строки returl остается в CGI-формате, после
   * чего оказывается невидим в getParameter на принимающей стороне.
   */
  echo url_for('taskState/postAnswers')
       .'?id='.$id
       .'&returl='.$retUrl;
  ?>"
  method="post"
>
  <span class="indentAction">
    <?php
    foreach ($form as $field)
    {
      echo $field->render();
    }
    ?>
    <input type="submit" value="Послать" alt="Отправить ответ(ы)" title="Отправить ответ(ы)"/>
  </span>
</form>