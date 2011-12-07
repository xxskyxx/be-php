<?php

/**
 * Фильр, ограничивающий интенсивность потока запросов в рамках сессии.
 */
class antiDosFilter extends sfFilter
{
  const INTEGRATION_INTERVAL = 5; //Интервал подсчета запросов, сек
  const REQUESTS_TRESHOLD = 15; //Максимально допустимое число запросов за интервал
  const BLOCK_INTERVAL = 30; //Интервал, на который блокируется доступ при превышении числа, сек

  public function execute($filterChain)
  {
    if ($this->isFirstCall())
    {
      $session = $this->getContext()->getUser();

      $timeNow = time();
      $lastTimeStamp = $session->getAttribute('lastTimeStamp', 0);
      if ($lastTimeStamp == 0)
      {
        //Подсчет только начался, отметим это.
        $session->setAttribute('lastTimeStamp', $timeNow);
        $lastTimeStamp = $timeNow;
      }
      $requestCount = $session->getAttribute('requestCount', 0);
      $blocked = $session->getAttribute('blocked', 0);

      if ($blocked == 0)
      {
        $requestCount = $requestCount + 1;
        $session->setAttribute('requestCount', $requestCount);

        if (($timeNow - $lastTimeStamp) < antiDosFilter::INTEGRATION_INTERVAL)
        {
          //Интервал интегрирования еще не закончился
          if ($requestCount >= antiDosFilter::REQUESTS_TRESHOLD)
          {
            //Превышено число запросов, блокируем
            $session->setAttribute('lastTimeStamp', $timeNow);
            $session->setAttribute('blocked', 1);
          }
        }
        else
        {
          //Интервал интегрирования закончился, начнем новый.
          $session->setAttribute('lastTimeStamp', $timeNow);
          $session->setAttribute('requestCount', 0);
        }
      }
      else
      {
        if ($lastTimeStamp + antiDosFilter::BLOCK_INTERVAL < $timeNow)
        {
          //Снимаем блокировку
          $session->setAttribute('blocked', 0);
          //Начинаем новый цикл счета
          $session->setAttribute('lastTimeStamp', $timeNow);
          $session->setAttribute('requestCount', 0);
        }
        else
        {
          include('../apps/frontend/config/error/unavailable_antidos.php');
          exit;
        }
      }
    }
    $filterChain->execute();
  }

}
?>
