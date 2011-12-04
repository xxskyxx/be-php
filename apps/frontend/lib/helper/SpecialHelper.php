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
  //Делитель не больше 1.5!
  return (1 + round($length / 1.6)); //Как точно считать - не ясно, пусть так будет.
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
  return '<div class="'.$class.'">'.$innerHtml.'</div>';
}

/**
 * Генерирует в поток вывода HTML-код одной строки с заголовком и несколькими значениями,
 * но только в том случае, если выполнено условие.
 * 
 * @param   string  $nameWidth  ширина колонки названия свойства (в единицах ex)
 * @param   string  $name       заголовок строки
 * @param   mixed   $value      значение строкой или значения массивом
 */
function render_named_line($nameWidth, $name, $values)
{
  $useValues = (is_array($values)) ? $values : array($values);
  
  echo "\n".'<div class="namedLineBox">'."\n";

  /* Финт ушами:
   * Конструкция style="width: 100%; max-width: ?" позволяет элементу
   * сжиматься менее max-width если max-width шире экрана,
   * в тоже время при достатке места он не будет шире max-width;
   */
  echo '<div class="namedLineName"'.(($nameWidth > 0) ? ' style="width: 100%; max-width:'.$nameWidth.'ex"' : '').'>';
  echo $name;
  echo '</div>';
  
  foreach ($useValues as $value)
  {
    echo '<div class="namedLineValue">';
    echo $value;
    echo '</div>';
  }
  
  echo "\n".'</div>'."\n";
}

/**
 * Генерирует в поток вывода HTML-код одной строки с заголовком и несколькими значениями,
 * но только в том случае, если выполнено условие.
 * 
 * @param   string          $nameWidth  ширина колонки названия свойства (в единицах ex)
 * @param   string          $name       заголовок строки
 * @param   array<string>   $value      значения
 */
function render_named_line_if($condition, $nameWidth, $name, $values)
{
  if ($condition)
  {
    render_named_line($nameWidth, $name, $values);
  } 
}

/**
 * Генерирует в поток вывода HTML-код "хлебных крошек" по списку указанных ссылок.
 *
 * @param   array   $links  Список HTML-кодов ссылок
 *
 * @return  string
 */
function render_breadcombs($links = null)
{
  echo '<div class="breadcombs">';
  echo '<div class="breadcomb">'.link_to('Главная', 'home/index').'</div>';
  if (is_array($links))
  {
    foreach ($links as $link)
    {
      echo '<div class="breadcomb">'.$link.'</div>';
    }
  }
  echo '</div>';
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
  echo '<h3 class="inline">'.$headerText.'</h3>';
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
    $htmlValues = array();
    array_push($htmlValues, $field->render());
    if ($field->hasError())
    {
      array_push($htmlValues, decorate_span('danger', $field->getError()));
    }
    $helps = explode('|', $field->getParent()->getWidget()->getHelp($field->getName()));
    foreach ($helps as $help)
    {
      array_push($htmlValues, $help);
    }
    render_named_line($width, $field->renderLabel(), $htmlValues);
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
  //Если это не Doctrine-форма, то с нее объект не получить
  if ( ! ($form instanceof sfFormDoctrine))
  {
    render_named_line($width, '<input type="submit" value="'.$commitLabel.'" />', array(($backHtml !== '') ? '' : $backHtml));
    return;
  }
  //Генерируем способ отправки
  if ( ! $form->getObject()->isNew())
  {
    echo '<input type="hidden" name="sf_method" value="put" />';
  }
  //Генерируем подвал формы
  render_named_line($width, '<input type="submit" value="'.$commitLabel.'" />', array(($form->getObject()->isNew()) ? '' : $backHtml));
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
  echo '<div class="columnName"'.(($width > 0) ? ' style="width:'.$width.'ex"' : '').'>'.$columnName.'</div>';
}

/**
 * Генерирует код значения в столбце
 * @param   string    $value  значение
 * @param   integer   $width  ширина в ex
 * @param   string    $align  выравнивание text-align
 */
function render_column_value($value, $width = 0, $align = '')
{
  $style = '';
  $style .= ($width > 0) ? 'width:'.$width.'ex;' : '';
  $style .= ($align !== '') ? 'text-align:'.$align.';' : '';
  echo '<div class="columnValue"'.(($style !== '') ? ' style="'.$style.'"' : '').'>'.$value.'</div>';
}
?>
