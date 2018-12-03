<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="article_list")
     *
     */
    public function index(Request $request)
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     * Param $article
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }


    /**
     * @param Tag $tag
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("blog/tag/{name}", name="tag")
     */
    public function showByTag(Tag $tag)
    {

        $tags = $tag->getArticles();


        return $this->render("article/tag.html.twig",
            [
                'tags' => $tags
            ]
            );

    }
}
