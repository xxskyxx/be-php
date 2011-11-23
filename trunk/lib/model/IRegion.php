<?php

/**
 * Интерфейс для сущностей, для которых имеет значение регион
 * 
 * @author brain
 */
interface IRegion
{

  /**
   * Возвращает коллекцию объектов, у которых регион равен указанному.
   * Если передан не экземпляр Region или регион по умолчанию - возвращает все.
   * 
   * @param   mixed               $region   Требуемый регион
   * @return  Doctrine_Collection
   */
  public static function byRegion( $region);
  
  /**
   * Возвращает регион объекта. Если он не указан, то вернет регион
   * по умолчанию.
   * 
   * @return  Doctrine_Record
   */
  public function getRegionSafe();
  
}

?>
