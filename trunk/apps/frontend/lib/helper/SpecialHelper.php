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
  return (1 + round($length / 1.7)); //Как точно считать - не ясно, пусть так будет.
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
 * Возвращает длину самого длинного значения из указанного поля коллекции.
 * При каких-либо проблемах вернет 0.
 * 
 * @param   Doctrine_Collection   $collection   коллекция
 * @param   string                $fieldName    имя поля
 * 
 * @return  integer
 */
function get_max_field_length(Doctrine_Collection $collection, $fieldName)
{
  if ($collection instanceof Doctrine_Collection)
  {
    $res = 0;
    foreach ($collection as $item)
    {
      if ($res < strlen($item->$fieldName))
      {
        $res = strlen($item->$fieldName);
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
 * Генерирует код оформления числа в зависимости от его знака
 * 
 * @param   integer   $value  исходное число
 * 
 * @return  string
 */
function decorate_number($value)
{
  if ($value == 0)
  {
    return '<span style="padding-left:1ex">'.$value.'</span>';
  }
  return ($value >= 0)
      ? decorate_span('info', '+'.$value)
      : decorate_span('warn', '&ndash;'.(-$value));
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
  return '<span class="'.$class.'">'.$innerHtml.'</span>';
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
  echo '<div class="propName"'.(($nameWidth > 0) ? ' style="width:'.$nameWidth.'ex"' : '').'>';
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
  $res = '\\\\&nbsp;'.link_to('Главная', 'home/index').'&nbsp;\\';
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
  echo '<div class="h3inline">'."\n";
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
 * Генерирует код одного поля формы с разметкой, аналогичной списку свойств.
 * 
 * @param   sfFormField   $field  поле для отображения
 * @param   integer       $width  ширина колонки с меткой поля, в ex
 * 
 * @return  string
 */
function render_form_field_using_div(sfFormField $field, $width)
{
  if ( ! $field->isHidden())
  {
    $htmlLabel = $field->renderLabel();
    $htmlField = $field->render();
    $htmlHelp = ' '.$field->getParent()->getWidget()->getHelp($field->getName());
    $htmlError = ($field->hasError())
        ? ' '.decorate_span('danger', $field->getError())
        : '';

    $html = $htmlField.$htmlError.$htmlHelp;
    render_property($htmlLabel, $html, $width);
  }
  else
  {
    echo '<div>'.$field->render().'</div>'."\n";
  }  
}

/**
 * Генерирует код для отправки формы с разметкой, аналогичной списку свойств.
 * 
 * @param   sfForm    $form         форма для отображения
 * @param   string    $commitLabel  название кнопки отправки формы
 * @param   string    $backHtml     html-код обратного перехода при отказе от отправки.
 * @param   integer   $width        ширина колонки с меткой поля, в ex
 */
function render_form_commit_using_div(sfForm $form, $commitLabel, $backHtml, $width)
{
  //Генерируем способ отправки
  if ( ! $form->getObject()->isNew())
  {
    echo '<input type="hidden" name="sf_method" value="put" />';
  } 
  //Генерируем подвал формы
  render_property('<input type="submit" value="'.$commitLabel.'" />', ($form->getObject()->isNew()) ? '' : $backHtml, $width);
}

/**
 * Генерирует код формы (без заголовка) с разметкой, аналогичной списку свойств.
 * 
 * @param   sfForm    $form         форма для отображения
 * @param   string    $commitLabel  название кнопки отправки формы
 * @param   string    $backHtml     html-код обратного перехода при отказе от отправки.
 */
function render_form_using_div(sfForm $form, $commitLabel, $backHtml)
{
  //Определим длину заголовков полей
  $width = get_text_block_size_ex(get_max_strlen($form->getWidgetSchema()->getLabels()));
  //Генерируем тело формы
  foreach($form as $field)
  {
    render_form_field_using_div($field, $width);
  }
  render_form_commit_using_div($form, $commitLabel, $backHtml, $width);
}

/**
 * Генерирует код заголовка столбца
 * 
 * @param   string    $columnName   заголовок
 * @param   integer   $width        ширина в ex
 */
function render_column_name($columnName, $width = 0)
{
  echo '<div class="columnName"';
  echo ($width > 0) ? ' style="width:'.$width.'ex"' : '';
  echo '>';
  echo $columnName;
  echo '</div>';
}

/**
 * Генерирует код значения в столбце
 * @param   string    $value  значение
 * @param   integer   $width  ширина в ex
 * @param   string    $align  выравнивание text-align
 */
function render_column_value($value, $width = 0, $align = '')
{
  echo '<div class="columnValue"';
  $style = '';
  $style .= ($width > 0) ? 'width:'.$width.'ex;' : '';
  $style .= ($align !== '') ? 'text-align:'.$align.';' : '';
  echo ($style !== '') ? ' style="'.$style.'"' : '';
  echo '>';
  echo $value;
  echo '</div>';
}
?>
