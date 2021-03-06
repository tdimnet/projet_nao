<?php

namespace NBGraphics\NewsBundle\Controller;

use NBGraphics\NewsBundle\Entity\Article;
use NBGraphics\NewsBundle\Entity\State;
use NBGraphics\NewsBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article controller.
 *
 * @Route("admin/actualites")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/", name="admin_article_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository(Article::class)->findArticles();

        $deleteForms = array();

        foreach ($articles as $article) {
            $deleteForms[$article->getId()] = $this->createDeleteForm($article)->createView();
        }

        return $this->render('NBGraphicsNewsBundle:article:index.html.twig', array(
            'articles' => $articles,
            'deleteForms' => $deleteForms
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/nouvelle-actualite", name="admin_article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $response = $this->get('news.crud.create')->createArticle($article);

            if ($response) {
                $this->addFlash('success', 'Article ajouté avec succès !');
                return $this->redirectToRoute('admin_article_show', array('id' => $article->getId()));
            } else {
                $this->addFlash('error', 'Erreur lors de l\'ajout de l\'article');
                return $this->redirectToRoute('admin_article_new');
            }

        }

        return $this->render('NBGraphicsNewsBundle:article:new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/fiche/{id}", name="admin_article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('NBGraphicsNewsBundle:article:show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/edition/{id}", name="admin_article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);
        $editForm = $this->createForm(ArticleType::class, $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $response = $this->get('news.crud.update')->updateArticle($article);

            if ($response) {
                $this->addFlash('success', 'Article modifié avec succès !');
                return $this->redirectToRoute('admin_article_show', array('id' => $article->getId()));
            } else {
                $this->addFlash('error', 'Erreur lors de la modification de l\'article');
                return $this->redirectToRoute('admin_article_edit', array('id' => $article->getId()));
            }

        }

        return $this->render('NBGraphicsNewsBundle:article:edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/suppression/{id}", name="admin_article_delete")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $response = $this->get('news.crud.delete')->deleteArticle($article);

            if ($response)
                $this->addFlash('success', 'Actualité supprimée avec succès !');
            else
                $this->addFlash('error', 'Erreur lors de la suppression de l\'actualité !');

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('NBGraphicsNewsBundle:article:delete.html.twig', array(
            'article' => $article,
            'delete_form'  => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function menuAction($active, $route)
    {
        return $this->render('NBGraphicsNewsBundle:article:menu.html.twig', array(
            'active' => ($active ? 'active' : ''),
            'route' => $route
        ));
    }
}
