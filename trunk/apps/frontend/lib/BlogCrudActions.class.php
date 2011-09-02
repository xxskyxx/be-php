<?php

abstract class BlogCrudActions extends MyActions
{
  
  abstract protected function canCreatePost(WebUser $webUser);
  abstract protected function canEditPost(WebUser $webUser, Post $post);
  abstract protected function canDeletePost(WebUser $webUser, Post $post);
  
  abstract protected function canCreateComment(WebUser $webUser);
  abstract protected function canEditComment(WebUser $webUser, Comment $comment);
  abstract protected function canDeleteComment(WebUser $webUser, Comment $comment);  
  
  public function executeNewPost(sfWebRequest $request)
  {
    $blog = Doctrine::getTable('Blog')->find($request->getParameter('blogId'));
    $this->forward404Unless($blog, 'Блог не найден');
    $this->errorRedirectUnless(
        $this->canCreatePost($this->sessionWebUser),
        Utils::cannotMessage($this->sessionWebUser->login, 'добавлять сообщения в этот блог')
    );
    $post = new Post();
    $post->blog_id = $blog->id;
    $post->web_user_id = $this->sessionWebUser->id;
    $this->form = new PostForm($post);
    $this->form->getWidget('ret_url')->setDefault(Utils::getReturnUrl($request));
    $this->setTemplate('newPost');
  }

  public function executeCreatePost(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->errorRedirectUnless(
        $this->canCreatePost($this->sessionWebUser),
        Utils::cannotMessage($this->sessionWebUser->login, 'добавлять сообщения в этот блог')
    );
    $this->form = new PostForm();
    $this->processPostForm($request, $this->form);
    $this->setTemplate('newPost');
  }

  public function executeEditPost(sfWebRequest $request)
  {
    $this->forward404Unless($post = Doctrine::getTable('Post')->find($request->getParameter('id')), 'Запись не найдена.');
    $this->errorRedirectUnless(
        $this->canEditPost($this->sessionWebUser, $post),
        Utils::cannotMessage($this->sessionWebUser->login, 'редактировать сообщения этого блога')
    );
    $this->form = new PostForm($post);
    $this->form->getWidget('ret_url')->setDefault(Utils::getReturnUrl($request));    
    $this->setTemplate('editPost');
  }

  public function executeUpdatePost(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($post = Doctrine::getTable('Post')->find($request->getParameter('id')), 'Запись не найдена.');
    $this->errorRedirectUnless(
        $this->canEditPost($this->sessionWebUser, $post),
        Utils::cannotMessage($this->sessionWebUser->login, 'редактировать сообщения этого блога')
    );
    $this->form = new PostForm($post);
    $this->processPostForm($request, $this->form);
    $this->setTemplate('editPost');
  }
 
  public function executeDeletePost(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($post = Doctrine::getTable('Post')->find($request->getParameter('id')), 'Запись не найдена.');
    $this->errorRedirectUnless(
        $this->canDeletePost($this->sessionWebUser, $post),
        Utils::cannotMessage($this->sessionWebUser->login, 'удалять сообщения этого блога')
    );
    $post->delete();
    $this->successRedirect('Сообщение успешно удалено.', Utils::getReturnUrl($request));
  }

  protected function processPostForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $object->create_time = $object->isNew() ? time() : $object->create_time;
      $object = $form->save();
      $this->successRedirect('Сообщение успешно сохранено.', $form->getValue('ret_url'));
    }
    else
    {
      $this->errorMessage('Сохранить запись не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }
  
  public function executeNewComment(sfWebRequest $request)
  {
    $post = Doctrine::getTable('Post')->find($request->getParameter('postId'));
    $this->forward404Unless($post, 'Собщение не найдено.');
    $this->errorRedirectUnless(
        $this->canCreateComment($this->sessionWebUser),
        Utils::cannotMessage($this->sessionWebUser->login, 'добавлять комментарии в этот блог')
    );
    $comment = new Comment();
    $comment->post_id = $post->id;
    $comment->web_user_id = $this->sessionWebUser->id;
    $this->form = new CommentForm($comment);
    $this->form->getWidget('ret_url')->setDefault(Utils::getReturnUrl($request));
    $this->setTemplate('newComment');
  }

  public function executeCreateComment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->errorRedirectUnless(
        $this->canCreateComment($this->sessionWebUser),
        Utils::cannotMessage($this->sessionWebUser->login, 'добавлять комментарии в этот блог')
    );
    $this->form = new CommentForm();
    $this->processCommentForm($request, $this->form);
    $this->setTemplate('newComment');
  }

  public function executeEditComment(sfWebRequest $request)
  {
    $this->forward404Unless($comment = Doctrine::getTable('Comment')->find($request->getParameter('id')), 'Комментарий не найден.');
    $this->errorRedirectUnless(
        $this->canEditComment($this->sessionWebUser, $comment),
        Utils::cannotMessage($this->sessionWebUser->login, 'редактировать комментарии в этом блоге')
    );
    $this->form = new CommentForm($comment);
    $this->form->getWidget('ret_url')->setDefault(Utils::getReturnUrl($request));
    $this->setTemplate('editComment');
  }

  public function executeUpdateComment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($comment = Doctrine::getTable('Comment')->find($request->getParameter('id')), 'Комментарий не найден.');
    $this->errorRedirectUnless(
        $this->canEditComment($this->sessionWebUser, $comment),
        Utils::cannotMessage($this->sessionWebUser->login, 'редактировать комментарии в этом блоге')
    );
    $this->form = new CommentForm($comment);
    $this->processCommentForm($request, $this->form);
    $this->setTemplate('editComment');
  }
 
  public function executeDeleteComment(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::DELETE));
    $request->checkCSRFProtection();
    $this->forward404Unless($comment = Doctrine::getTable('Comment')->find($request->getParameter('id')), 'Комментарий не найден.');
    $this->errorRedirectUnless(
        $this->canDeleteComment($this->sessionWebUser, $comment),
        Utils::cannotMessage($this->sessionWebUser->login, 'удалять комментарии в этом блоге')
    );
    $comment->delete();
    $this->successRedirect('Комментарий успешно удален.', Utils::getReturnUrl($request));
  }

  protected function processCommentForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $object = $form->updateObject();
      $object->create_time = $object->isNew() ? time() : $object->create_time;
      $object = $form->save();
      $this->successRedirect('Комментарий успешно сохранен.', $form->getValue('ret_url'));
    }
    else
    {
      $this->errorMessage('Сохранить комментарий не удалось. Исправьте ошибки и попробуйте снова.');
    }
  }
    
}

?>
