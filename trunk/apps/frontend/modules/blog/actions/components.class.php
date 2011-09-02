<?php

class blogComponents extends sfComponents
{

  /**
   * Формирует данные для отображения блога в стандартном виде.
   * 
   * @param   string    $id               Id блога
   * @param   string    $webUserId        Идентификатор текущего пользователя
   * @param   integer   $page             Текущая страница
   * @param   integer   $expandedPostId   Идентификатор сообщения, где показаны все комментарии
   * @param   boolean   $readOnly         Блог отображается только для чтения
   * @param   string    $backUrl          Ссылка, на которую над вернуться после операций редактирования сообщений/комментариев
   * @param   string    $editorModule     Название модуля, обрабатывающего операции редактирования сообщений/комментариев
   * @param   boolean   $canPost          Текущий пользователь может оставлять сообщения
   * @param   boolean   $canComment       Текущий пользователь может оставлять комментарии
   * @param   boolean   $canEditSelf      Текущий пользователь может редактировать свои сообщения/комментарии
   * @param   boolean   $canEditAny       Текущий пользователь может редактировать сообщения/комментарии
   * @param   boolean   $canDeleteSelf    Текущий пользователь может удалять свои сообщения/комментариия
   * @param   boolean   $canDeleteAny     Текущий пользователь может удалять сообщения/комментарии
   */
  public function executeBlog()
  {
    $this->_blog = Doctrine::getTable('Blog')->find($this->id);
    if ($this->_blog)
    {
      $this->_blogContext = new BlogContext();
      $this->_blogContext->webUserId = isset($this->webUserId) ? $this->webUserId : 0;
      $this->_blogContext->pageCount = $this->_blog->getMaxPostsPage();
      $this->_blogContext->page = isset($this->page) ? $this->page : 1;
      $this->_blogContext->page = ($this->page > 1) ? $this->page : 1;
      $this->_blogContext->page = ($this->_blogContext->page > $this->_blogContext->pageCount) ? $this->_blogContext->pageCount : $this->_blogContext->page;
      $this->_blogContext->expandedPostId = isset($this->expandedPostId) ? $this->expandedPostId : 0;
      $this->_blogContext->readOnly = isset($this->readOnly) ? $this->readOnly : false;
      $this->_blogContext->readOnly = $this->_blogContext->readOnly || ($this->_blogContext->editorModule === '') || ($this->_blogContext->webUserId == 0);
      $this->_blogContext->backUrl = isset($this->backUrl) ? $this->backUrl : 'home/index';
      $this->_blogContext->editorModule = isset($this->editorModule) ? $this->editorModule : 'moderation';

      $this->_blogContext->canPost = isset($this->canPost) ? $this->canPost : false;
      $this->_blogContext->canComment = isset($this->canComment) ? $this->canComment : false;
      $this->_blogContext->canEditSelf = isset($this->canEditSelf) ? $this->canEditSelf : false;
      $this->_blogContext->canEditAny = isset($this->canEditAny) ? $this->canEditAny : false;
      $this->_blogContext->canDeleteSelf = isset($this->canDeleteSelf) ? $this->canDeleteSelf : false;
      $this->_blogContext->canDeleteAny = isset($this->canDeleteAny) ? $this->canDeleteAny : false;
    
      $this->_posts = $this->_blog->getPostsOnPage($this->_blogContext->page);
    }
  }
  
  /**
   * Формирует данные для отображения одного сообщения блога.
   * 
   * @param   BlogContext           $blogContext  Контекст блога
   * @param   Doctrine_record<Post> $post         Сообщение
   */
  public function executePost()
  {
    $this->_post = isset($this->post) ? $this->post : false;
    $this->_blogContext = isset($this->blogContext) ? $this->blogContext : false;
    if ($this->_post && $this->_blogContext)
    {
      $this->_comments = $this->_post->getLastComments(($this->_post->id == $this->blogContext->expandedPostId) ? 0 : Post::LAST_COMMENTS_MAX);
      $this->_collapsed = $this->_comments->count() < $this->_post->comments->count();
    }
  }

}
?>