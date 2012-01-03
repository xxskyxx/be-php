<?php

/**
 * article actions.
 *
 * @package    sf
 * @subpackage article
 * @author     VozdvIN
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleActions extends MyActions
{

  public function executeIndex(sfWebRequest $request)
  {
    if ( ! $this->session->isAuthenticated() )
    {
      $this->errorRedirect('Список статей доступен только после входа.', 'auth/login');
    }
    $this->_articles = Doctrine_Core::getTable('Article')
      ->createQuery('a')
      ->orderBy('name')
      ->execute();
    $this->_sessionWebUserId = $this->session->isAuthenticated() ? $this->sessionWebUser->id : -1;
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->_article = Article::byId($request->getParameter('id'));
    $this->errorRedirectUnless($this->_article, 'Статья не найдена.', 'article/index');
    $this->prepareShow();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->session->isAuthenticated(), 'Вы не можете писать статьи, пока не войдете.');
    $this->redirectIfCanNotCreateArticles();
    $this->form = new ArticleForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->session->isAuthenticated(), 'Вы не можете писать статьи, пока не войдете.');
    $this->redirectIfCanNotCreateArticles();
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new ArticleForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->session->isAuthenticated(), 'Вы не можете править статьи, пока не войдете.');
    $article = Article::byId($request->getParameter('id'));
    $this->errorRedirectUnless($article, 'Статья не найдена.', 'article/index');
    $this->errorRedirectUnless($article->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser->login, 'править статью', 'article/show?id='.$article->id));
    $this->form = new ArticleForm($article);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->session->isAuthenticated(), 'Вы не можете править статьи, пока не войдете.');
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));

    $article = Article::byId($request->getParameter('id'));
    $this->errorRedirectUnless($article, 'Статья не найдена.', 'article/index');
    $this->errorRedirectUnless($article->canBeManaged($this->sessionWebUser), Utils::cannotMessage($this->sessionWebUser, 'править статью', 'article/show?id='.$article->id));

    $this->form = new ArticleForm($article);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->errorRedirectUnless($this->session->isAuthenticated(), 'Вы не можете удалять статьи, пока не войдете.');
    $request->checkCSRFProtection();
    $article = Article::byId($request->getParameter('id'));
    $this->errorRedirectUnless($article, 'Статья не найдена.', 'article/index');
    
    if (   $article->name == 'Инструкции'
        || $article->name == 'Новости'
        || $article->name == 'Разделы')
    {
      $this->errorRedirect('Статья "'.$article->name.'" является служебной и не может быть удалена.', 'article/show?id='.$article->id);
    }
    
    $article->delete();
    
    $this->successRedirect('Статья удалена.', 'article/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $this->errorRedirectUnless($this->session->isAuthenticated(), 'Вы не можете править статьи, пока не войдете.');
    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $article = $form->updateObject();
      $existedArticle = Article::byName($article->name);
      //TODO: Почему не работает уникальный индекс в БД по ключу и названию статьи?
      //Приходится делать вручную...
      if ( ( ! $existedArticle) || ( ! $article->isNew()) )
      {
        if ($article->isNew())
        {
          $article->created_at = time();
          $article->assignAuthor($this->sessionWebUser);
          $article = $form->save();
          $this->successRedirect('Статья создана.', 'article/show?id='.$article->id);
        }
        else
        {
          $article = $form->save();
          $this->successRedirect('Статья изменена.', 'article/show?id='.$article->id);
        }
      }
      else
      {
        $this->errorMessage('Сохранить статью не удалось. Статья с таким названием уже существует.');
      }
    }
    else
    {
      $this->errorMessage('Сохранить статью не удалось. Исправьте ошибки и попробуйте снова.');
    }    
  }

  public function executeBy(sfWebRequest $request)
  {
    $this->_articleName = $request->getParameter('name');
    $this->_article = Article::byName($this->_articleName);
    if ($this->_article)
    {
      $this->prepareShow();
      $this->setTemplate('show');
    }
    else
    {
      $this->_children = Doctrine::getTable('Article')
          ->createQuery('a')
          ->select()
          ->where('a.path LIKE ?', array('%'.$this->_articleName.'%'))
          ->orderBy('a.name')
          ->execute();
    }
  }

  protected function prepareShow()
  {
    $this->_authenticated = $this->session->isAuthenticated();
    $this->_isModer = $this->_authenticated
                      && ($this->sessionWebUser->can(Permission::ARTICLE_MODER, $this->_article->id));
    $this->_canEdit = $this->_authenticated
                      && ($this->_article->canBeManaged($this->sessionWebUser));    
  }
  
  protected function redirectIfCanNotCreateArticles()
  {
    if ( ! $this->session->isAuthenticated())
    {
      $this->errorRedirect('Вы не можете писать статьи, пока не войдете.');
    }
    else
    {
      if (( ! $this->sessionWebUser->can(Permission::ARTICLE_MANIAC, 0))
          && ($this->sessionWebUser->articles->count() >= Article::MAX_ARTICLES_PER_USER))
      {
          $this->errorRedirect('Вы исчерпали лимит статей, равный '.Article::MAX_ARTICLES_PER_USER.'. Перед созданием новой статьи удалите одну из своих статей.', 'article/index');
      }
    }
  }
}
