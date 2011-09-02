<?php

class BlogContext
{
  
  /** Идентификатор текущего пользователя
   * @var string
   */
  public $webUserId;
  
  /** Количество страниц
   * @var integer
   */
  public $pageCount;
  
  /** Текущая страница
   * @var integer
   */
  public $page;

  /** Идентификатор сообщения, где показаны все комментарии
   * @var integer
   */
  public $expandedPostId;

  /** Блог отображается только для чтения
   * @var boolean
   */
  public $readOnly;

  /** Ссылка, на которую над вернуться после операций редактирования сообщений/комментариев
   * @var string
   */
  public $backUrl;

  /** Название модуля, обрабатывающего операции редактирования сообщений/комментариев
   * @var string
   */  
  public $editorModule;

  /** Текущий пользователь может оставлять сообщения
   * @var boolean
   */
  public $canPost;

  /** Текущий пользователь может оставлять комментарии
   * @var boolean
   */
  public $canComment;

  /** Текущий пользователь может редактировать свои сообщения/комментарии
   * @var boolean
   */
  public $canEditSelf;

  /** Текущий пользователь может редактировать сообщения/комментарии
   * @var boolean
   */
  public $canEditAny;

  /** Текущий пользователь может удалять свои сообщения/комментарии
   * @var boolean
   */
  public $canDeleteSelf;

  /** Текущий пользователь может удалять сообщения/комментарии
   * @var boolean
   */
  public $canDeleteAny;
  
}

?>
