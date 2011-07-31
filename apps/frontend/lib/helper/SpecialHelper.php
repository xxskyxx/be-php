<?php

/**
 * Рассчитывает ширину блока (в ex) для размещения текста указанной длины.
 * 
 * @param   mixed   $value  (Число) - длина строки, (Строка) - как есть.
 * 
 * @return  integer         ширина блока в единицах ex
 */
function get_text_block_size_ex($value)
{
  $length = (is_string($value)) ? strlen($value) : $value;
  return (1 + round($length / 1.4)); //Как точно считать - не ясно, пусть так будет.
}

/**
 * Возвращает длину самой длинной строки из указанного массива.
 * При каких-либо проблемах вернет 0.
 * 
 * @param   array   $array  массив со строками
 * 
 * @return  integer
 */
function get_max_strlen($array)
{
  if (is_array($array))
  {
    $res = 0;
    foreach ($array as $value)
    {
      if (is_string($value) && ($res < strlen($value)))
      {
        $res = strlen($value);
      }
    }
    return $res;
  }
  else
  {
    return 0;
  }
}

/**
 * Возвращает HTML-код, обрамляющий указанный, для выделения соответствующим span-классом.
 * 
 * @param   string   $class       CSS-класс тега span
 * @param   string   $innerHtml   HTML-код для обрамления
 *  
 * @return  string 
 */
function decorate_span($class, $innerHtml)
{
  return '<span class="'.$class.'">'.$innerHtml.'</span>'."\n";
}

/**
 * Возвращает HTML-код, обрамляющий указанный, для выделения соответствующим div-классом.
 * 
 * @param   string   $class       CSS-класс тега span
 * @param   string   $innerHtml   HTML-код для обрамления
 *  
 * @return  string 
 */
function decorate_div($class, $innerHtml)
{
  return '<div class="'.$class.'">'.$innerHtml.'</div>'."\n";
}

/**
 * Генерирует в поток вывода HTML-код одной строки для свойства и его значения
 * 
 * @param   string  $name       имя свойства
 * @param   string  $value      значение свойства
 * @param   string  $nameWidth  ширина колонки названия свойства (в единицах ex)
 * 
 * @return  string
 */
function render_property($name, $value, $nameWidth = 0)
{
  //Открываем блок строки
  echo '<div>';
  //Формируем колонку названия свойства
  echo '<div class="propName"';
  if ($nameWidth > 0)
  { 
    echo ' style="width: '.$nameWidth.'ex"';
  }
  echo '>';
  echo $name;
  echo '</div>';
  echo ' ';
  //Формируем колонку значения свойства
  echo '<div class="propValue">';
  echo $value;
  echo '</div>';
  //Закрываем блок строки
  echo '</div>'."\n";
}

/**
 * Генерирует в поток вывода HTML-код одной строки для свойства и его значения,
 * но только в том случае, если выполнено условие.
 * 
 * @param   string  $name       имя свойства
 * @param   string  $value      значение свойства
 * @param   string  $nameWidth  ширина колонки названия свойства (в единицах ex)
 * 
 * @return  string
 */
function render_property_if($condition, $name, $value, $nameWidth = 0)
{
  if ($condition)
  {
    render_property($name, $value, $nameWidth);
  }
}

/**
 * Генерирует в поток вывода HTML-код "хлебных крошек" по списку указанных ссылок.
 * 
 * @param   array   $links  Список HTML-кодов ссылок, строки
 * 
 * @return  string 
 */
function render_breadcombs($links = null)
{
  $res = '<img src="/images/favicon.png" alt="[BE]" onClick="document.location=\'/home/index\'" />&nbsp;<a href="/home/index">Главная</a>&nbsp;\\';
  if (is_array($links))
  {
    foreach ($links as $link)
    {
      $res .= ' '.$link.'&nbsp;\\';
    }
  }
  echo '<div class="breadcombs">'.$res.'</div>';
}

/**
 * Генерирует в поток вывода HTML-код начала заколовка h3 с встроенными ссылками.
 * 
 * @param   string   $headerText  текст заголовка
 * 
 * @return  string 
 */
function render_h3_inline_begin($headerText)
{
  //Общий контейнер
  echo '<div class="hr">'."\n";
  //Заголовок
  echo '<h3 class="inline">'.$headerText.'</h3>'."\n";
}

/**
 * Генерирует в поток вывода HTML-код завершения заколовка h3 с встроенными ссылками.
 * 
 * @return  string 
 */
function render_h3_inline_end()
{
  echo '</div>'."\n";
}

/**
 * Генерирует код содержания формы (без ссылок управления) с разметкой, аналогичной списку свойств.
 * 
 * @param   sfForm    $form         форма для отображения
 * @param   string    $commitLabel  название кнопки отправки формы
 * @param   string    $backHtml     html-код обратного перехода при отказе от отправки.
 * 
 * @return  string
 */
function render_form_using_div($form, $commitLabel, $backHtml)
{
  //Определим длину заголовков полей
  $width = get_text_block_size_ex(get_max_strlen($form->getWidgetSchema()->getLabels()));
  //Генерируем тело формы
  foreach($form as $field)
  {
    if ( ! $field->isHidden())
    {
      $value = $field->render();
      if ($field->hasError())
      {
        $value .= ' '.decorate_span('danger', $field->getError());
      }
      render_property($field->renderLabel(), $value, $width);
    }
    else
    {
      echo $field->render();
    }
  }
  //Генерируем способ отправки
  if ( ! $form->getObject()->isNew())
  {
    echo '<input type="hidden" name="sf_method" value="put" />';
  } 
  //Генерируем подвал формы
  render_property('<input type="submit" value="'.$commitLabel.'" />', ($form->getObject()->isNew()) ? '' : $backHtml, $width);
}
?>
