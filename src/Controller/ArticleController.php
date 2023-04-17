<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
   
    #[Route('/articles', name: 'article_index')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->getByDate();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/articles-by-category/{id}', name: 'article_category')]
    public function findByCategory(int $id, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->getByCategory($id);
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render("article/show.html.twig", [
            "article" => $article
        ]);
    }

    
    #[Route('/article-create', name: 'article_create')]
    public function create(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$articleRepository->persist($article);
            //$articleRepository->flush();

            $articleRepository->save($article, true);

            return $this->redirectToRoute('article_index');
        }
        
        return $this->render('article/create.html.twig', [
            'articles' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'article_edit')]
    public function edit(Article $article, Request $request, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$articleRepository->persist($article);
            //$articleRepository->flush();

            $articleRepository->save($article, true);

            return $this->redirectToRoute('article_index');
        }
        
        return $this->render('article/edit.html.twig', [
            'articles' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'article_delete')]
    public function delete(Article $article, ArticleRepository $articleRepository): Response
    {
            $articleRepository->remove($article, true);
            return $this->redirectToRoute('article_index');
    }

    
    #[Route('/article-like/{id}', name: 'article_like')]
    public function like(Article $article, ArticleRepository $articleRepository): Response
    {
        if($article->isLiked()) 
        {
            $article->setLiked(false);
            $articleRepository->save($article, true);

        } else {
            $article->setLiked(true);
            $articleRepository->save($article, true);

        }
        return $this->redirectToRoute('article_index');
    }

}
