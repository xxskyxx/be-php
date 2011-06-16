<?php

class Timing
{
  const NO_DATE = '____:__:__';
  const NO_TIME = '--:--:--';

  /**
   * Проверяет, отстоит ли $testTime от $baseTime не менее чем на $interval секунд.
   *
   * @param   integer   $testTime   Проверяемое время (время Unix)
   * @param   integer   $interval   Интервал в секундах
   * @param   integer   $baseTime   Время, от котого идет отсчет (время Unix)
   * @return  boolean
   */
  public static function isExpired($testTime, $interval, $baseTime)
  {
    return ($testTime - $baseTime) >= $interval;
  }

  /**
   * Возвращает отформатированную дату "ГГГГ-ММ-ДД ЧЧ:ММ:СС", соответствующую указанному времени.
   *
   * @param   integer   $timeDate   Время Unix
   * @return  string
   */
  public static function dateToStr($timeDate)
  {
    if ($timeDate == 0)
    {
      return Timing::NO_DATE.' '.Timing::NO_TIME;
    }
    return date('Y-m-d H:i:s', $timeDate);
  }

  /**
   * Возвращает отформатированное время "ЧЧ:ММ:СС", соответствующую указанному времени.
   *
   * @param   integer   $time       Время Unix
   * @return  string
   */
  public static function timeToStr($time)
  {
    if ($time == 0)
    {
      return Timing::NO_TIME;
    }
    return date('H:i:s', $time);
  }

  /**
   * Указанный в секундах интервал преобразует в соответствующее время "ЧЧ:ММ:СС".
   * gmdate используется потому что интервал не нужно корректировать на местное время.
   *
   * @param   integer   $time       Интервал в секундах (время Unix)
   * @return  string
   */
  public static function intervalToStr($time)
  {
    if ($time == 0)
    {
      return Timing::NO_TIME;
    }
    return gmdate('H:i:s', $time);
  }

  /**
   * Конвертирует строку вида "ГГГГ-ММ-ДД ЧЧ:ММ:СС" в метку времени Unix.
   * 
   * @todo Надо это как-то нормально реализовать, а не так криво. Как вообще mySql хранит timestamp?
   * 
   * @param   string  $dateStr
   * @return  integer
   */
  public static function strToDate($dateStr)
  {
    try
    {
      $parts = split(' ', $dateStr);
      $date = split('-', $parts[0]);
      $time = split(':', $parts[1]);
      return mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
    }
    catch (Exception $exc)
    {
      return 'НеверныйФорматДаты';
    }
  }

}

/**
 * Класс вспомогательных инструментов
 */
class Utils
{
  const PASSWORD_SALT = 'cHaNgEtHiS';
  const IMG_BUTTONS_PATH = '/images/buttons/';
  const IMG_BUTTONS_STYLE = 'imageButton';
  
  /**
   * Возвращает хэш пароля с учетом "соления".
   *
   * @param   string  $password нешифрованный пароль
   * @return  string
   */
  public static function saltedPwdHash($password)
  {
    return md5($password.Utils::PASSWORD_SALT);
  }

  /**
   * Кодирует адрес обратного перехода, чтобы при записи в URL, он не нарушал правил маршрутизации
   *
   * @param   string  $sourceUrl  Исходный адрес
   * @return  string              Шифрованный адрес
   */
  public static function encodeSafeUrl($sourceUrl)
  {
    return base64_encode($sourceUrl);
  }

  /**
   * Раскодирует адрес обратного перехода (после encodeSafeUrl)
   *
   * @param   string  $encodedUrl   Шифрованный адрес
   * @return  string                Исходный адрес
   */
  public static function decodeSafeUrl($encodedUrl)
  {
    return base64_decode($encodedUrl);
  }

  /**
   * Возвращает все хранимые экземпляры указанного класса.
   *
   * @param   string              $className  Имя класса
   * @param   string              $orderBy    Поле для сортировки
   * @return  Doctrine_Collection             Или false, если нет результатов.
   */
  public static function all($className, $orderBy = false)
  {
    if (!$orderBy)
    {
      $res = Doctrine::getTable($className)->findAll();
    }
    else
    {
      $res = Doctrine::getTable($className)
              ->createQuery('wu')
              ->select()
              ->orderBy($orderBy)
              ->execute();
    }
    return ( ($res->count() > 0)
        ? $res
        : false );
  }

  /**
   * Возвращает экземпляры указанного класса по ключу.
   * Не боится (в отличии от find) нулевых и отрицательных ключей.
   *
   * @param   string              $className  Имя класса
   * @param   string              $id         Ключ хранимого экземпляра
   * @return  Doctrine_Record     Или false, если нет результатов.
   */
  public static function byId($className, $id)
  {
    if ($id <= 0)
    {
      return false;
    }
    return Doctrine::getTable($className)->find($id);
  }

  /**
   * Возвращает все экземпляры класса, у которых в поле есть указанное значение.
   * Определяет факт, что найдена только она запись, тогда ее и возвращает именно как запись.
   *
   * @param  string   $fieldName    Поле, в котором идет поиск
   * @param  string   $fieldValue   Искомое значение
   * @return mixed                  Doctrine_Collection если найдено более одной записи, Doctrine_Record если найдена только одна запись. False если нет результатов.
   */
  public static function byField($className, $fieldName, $fieldValue)
  {
    $res = Doctrine::getTable($className)->findBy($fieldName, $fieldValue);
    switch ($res->count())
    {
      case (0):
        //Нет результатов
        return false;
      case (1):
        //Коллекция из одной записи, запись и вернем.
        return $res[0];
      default:
        //Коллекция.
        return $res;
    }
  }

  /**
   * Готовит сообщение о нехватке прав по указанным параметрам.
   *
   * @param   string  $userName       Имя пользователя
   * @param   string  $messageEnding  Описание действия
   * @return  string
   */
  public static function cannotMessage($userName = 'Кто-то (скорее всего вы)', $messageEnding = 'так борзеть')
  {
    return $userName.' не имеет полномочий чтобы '.$messageEnding.'.';
  }

  /**
   * Формирует HTML-код кнопки для указанного действия методом POST с CSRF-подписью.
   * В отличие от button_to, назначение адреса перехода происходит только при
   * утвердительном ответе на вопрос, поэтому можно использовать не только в формах.
   * Если вопрос не указан, переходит сразу.
   *
   * @param   string  $caption          Надпись на кнопке
   * @param   string  $url              Адрес перехода
   * @param   string  $method           Вариант метода
   * @param   string  $confirmQuestion  Вопрос для выполнения
   * @return  string
   */
  public static function buttonTo($caption, $url, $method='post', $confirmQuestion = '')
  {
    return '<input value="'.$caption.'" type="button" onclick="'.Utils::urlRedirectScript($url, $method, $confirmQuestion).'" />';
  }

  /**
   * Аналог buttonTo, но картинкой.
   *
   * @param   string  $hint             Всплывающая подсказка, она же текст замещения.
   * @param   string  $uniqueName       Служебное название кнопки, должно быть иникально на странице.
   * @param   string  $normalPicture    Имя файла с картинкой обычного состояния кнопки.
   * @param   string  $hoverPicture     Имя файла с картинкой подствеченного состояния кнопки.
   * @param   string  $pressedPicture   Имя файла с картинкой нажатого состояния кнопки.
   * @param   string  $url              Адрес перехода
   * @param   string  $method           Вариант метода
   * @param   string  $confirmQuestion  Вопрос для выполнения
   * @return  string
   */
  public static function imgButtonTo($hint, $uniqueName, $normalPicture, $hoverPicture, $pressedPicture, $url, $method='post', $confirmQuestion = '')
  {
    return '<div class="imageButton"><img title="'.$hint.'" id="'.$uniqueName.'" src="'.$normalPicture.'" alt="'.$hint.'" onclick="'.Utils::urlRedirectScript($url, $method, $confirmQuestion).'" onmouseup="'.$uniqueName.'.src=\''.$normalPicture.'\'" onmouseout="'.$uniqueName.'.src=\''.$normalPicture.'\'" onmouseover="'.$uniqueName.'.src=\''.$hoverPicture.'\'" onmousedown="'.$uniqueName.'.src=\''.$pressedPicture.'\'" /></div>';
  }

  /**
   * Кнопка с картинкой с сокращенным указанием имен картинок:
   * - Обычное состояние кнопки - $pictureAlias.'.png',
   * - Подсвеченное состояние -  $pictureAlias.'hover.png',
   * - Нажатое состояние - $pictureAlias.'Pressed.png',
   *
   * @param   string  $hint             Всплывающая подсказка, она же текст замещения.
   * @param   string  $uniqueName       Служебное название кнопки, должно быть иникально на странице.
   * @param   string  $pictureAlias     Общая часть группы файлов с картинками состояний кнопки.
   * @param   string  $url              Адрес перехода
   * @param   string  $method           Вариант метода
   * @param   string  $confirmQuestion  Вопрос для выполнения
   * @return  string
   */
  public static function imgButtonTemplate($hint, $uniqueName, $pictureAlias, $url, $method='post', $confirmQuestion = '')
  {
    return Utils::imgButtonTo($hint, $uniqueName, Utils::IMG_BUTTONS_PATH.$pictureAlias.'.png', Utils::IMG_BUTTONS_PATH.$pictureAlias.'hover.png', Utils::IMG_BUTTONS_PATH.$pictureAlias.'Pressed.png', $url, $method, $confirmQuestion);
  }

  /**
   * Преобразует текст с переносами строк (любого вида) в текст с <div>-блоками
   *
   * @param   string  $text   Данные из БД
   * @return  string          HTML-код
   */
  public static function convertEOLNtoDIV($text)
  {
    $res = preg_replace('/\n\r|\r\n/', '</div><div>', $text);
    return '<div>'.$res.'</div>';
  }

  /**
   * Расшифровывает BB-код в исходной строке.
   *
   * @param   string  $text         Исходный текст
   * @param   boolean $monospaced   Использовать моноширинный шрифт
   * @return  string                HTML-код
   */
  public static function decodeBB($text, $monospaced = false)
  {
    $res = $text;
    //Принудительные пробелы
    $res = preg_replace('/\[_\]/', '&nbsp;', $res);
    $res = preg_replace('/\[tab\]/', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $res);
    //Формат текста
    $res = preg_replace('/\[b\]([^\]]+)\[\/b\]/', '<span style="font-weight:bold">$1</span>', $res);
    $res = preg_replace('/\[i\]([^\]]+)\[\/i\]/', '<span style="font-style:italic">$1</span>', $res);
    $res = preg_replace('/\[u\]([^\]]+)\[\/u\]/', '<span style="text-decoration:underline">$1</span>', $res);
    $res = preg_replace('/\[s\]([^\]]+)\[\/s\]/', '<span style="text-decoration:line-through">$1</span>', $res);
    $res = preg_replace('/\[del\]([^\]]+)\[\/del\]/', '<span style="text-decoration:line-through">$1</span>', $res);
    $res = preg_replace('/\[color=(#[0-9a-fA-F]{6}|[a-z-]+)]([^\]]+)\[\/color\]/', '<span style="color:$1">$2</span>', $res);
    $res = preg_replace('/\[back=(#[0-9a-fA-F]{6}|[a-z-]+)]([^\]]+)\[\/back\]/', '<span style="background-color:$1">$2</span>', $res);
    //Формат текста предопределенный
    $res = preg_replace('/\[rem\]([^\]]+)\[\/rem\]/', '<span class="info">$1</span>', $res);
    $res = preg_replace('/\[warn\]([^\]]+)\[\/warn\]/', '<span class="warn">$1</span>', $res);
    $res = preg_replace('/\[danger\]([^\]]+)\[\/danger\]/', '<span class="danger">$1</span>', $res);
    //Ссылки
    $res = preg_replace('/\[url\]([^\]]+)\[\/url\]/', '<a href="$1">$1</a>', $res);
    $res = preg_replace('/\[url=([^\]]+)]([^\]]+)\[\/url\]/', '<a href="$1">$2</a>', $res);
    //Картинки
    $res = preg_replace('/\[img\]([^\]]+)\[\/img\]/', '<img src="$1" alt="$1" />', $res);
    $res = preg_replace('/\[img=([^\]]+)]/', '<img src="$1" alt="$1" />', $res);
    //Оформление переносов строк
    $res = preg_replace('/\n\r|\r\n/', '</div><div>', $res);
    //Оформление пустых строк
    $res = preg_replace('/<div><\/div>/', '<div class="spaceAfter"></div>', $res);
    //Усё...
    return $monospaced
        ? '<div style="font-family:monospace">'.$res.'</div>'
        : $res;
  }

  /**
   * Корректно формирует значение по умолчанию параметра returl.
   *
   * @param   sfRequest   $request  HTTP-запрос
   * @param   string      $default  Значение по умолчанию
   * @return  string
   */
  public static function getReturnUrl(sfRequest $request, $default = 'home/index')
  {
    $retUrl = $request->getParameter('returl', false);
    if (!$retUrl)
    {
      return $default;
    }
    else
    {
      return Utils::decodeSafeUrl($retUrl);
    }
  }

  /**
   * Формирует HTML-код для отображения строки по вертикали.
   * 
   * @todo: Пока заглушка, надо разобраться, как вставлять пробелы в UTF8-строку.
   * 
   * @return string
   */
  public static function renderVertical($sourceString)
  {
    return $sourceString;
  }
  
  
  //// Self ////

  /**
   * Формирует HTML-код c JavaScript-обработчиком для перехода по указанному адресу.
   * Используется метод POST и CSRF-проверка.
   * При указании вопроса c подтверждением - переход только при утвердительном ответе, иначе сразу.
   * @param   string  url
   * @param   string  $method
   * @param   string  $confirmQuestion
   * @return  string
   */
  protected static function urlRedirectScript($url, $method='post', $confirmQuestion = '')
  {
    if ($confirmQuestion != '')
    {
      $proc = "if(confirm('".htmlspecialchars($confirmQuestion)."')){";
    }
    else
    {
      $proc = '';
    }

    $proc .= "var f=document.createElement('form');f.style.display='none';this.parentNode.appendChild(f);f.method='post';f.action='".url_for($url)."';var m=document.createElement('input');m.setAttribute('type','hidden');m.setAttribute('name','sf_method');m.setAttribute('value','".htmlspecialchars($method)."');f.appendChild(m);";

    $form = new BaseForm();
    if ($form->isCSRFProtected())
    {
      $proc .= "var m=document.createElement('input');m.setAttribute('name','".$form->getCSRFFieldName()."');m.setAttribute('value','".$form->getCSRFToken()."');f.appendChild(m);";
    };
    $proc .= "f.submit();";
    if ($confirmQuestion != '')
    {
      $proc .= "};";
    }

    return $proc;
  }

}

?>
