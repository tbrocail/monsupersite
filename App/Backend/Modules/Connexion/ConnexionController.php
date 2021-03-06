<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \Entity\News;
use \Entity\Author;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\AuthorFormBuilder;
use \OCFram\FormHandler;

class ConnexionController extends BackController
{
  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Connexion');
    $this->page->addVar('global_error_code',500);
    $this->page->addVar('global_error_message','Vous devez être connecté pour acceder à cette action');
    
    if ($request->postExists('login'))
    {
      $login = $request->postData('login');
      $password = $request->postData('password');
      
      
      $author = $this->managers->getManagerOf('Authors')->getUnique($login);
      
      if ($author != null && substr(crypt($password, $author->salt()),0,50) == $author->password())
      {
        $this->app->user()->setAuthenticated(true);
        $this->app->user()->setAttribute('login', $login); 
        if ($author->authors_fk_type == 1)
        {          
          $this->app->user()->setAuthenticatedAdmin(true);
        }   
        $this->app->httpResponse()->redirect('/admin/');
        

      }
      else
      {

        $this->app->user()->setFlash('Le auteur ou le mot de passe est incorrect.');
      }
    }
  }
}
?>