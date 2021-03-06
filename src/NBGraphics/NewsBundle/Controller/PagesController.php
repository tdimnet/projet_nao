<?php

namespace NBGraphics\NewsBundle\Controller;

use NBGraphics\NewsBundle\Entity\Article;
use NBGraphics\NewsBundle\Entity\State;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Article controller.
 *
 * @Route("actualites")
 */
class PagesController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/{page}", name="article_list", defaults={"page": 1})
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $page = (int)$page;

        $em = $this->getDoctrine()->getManager();

        $maxArticles = (int)$this->getParameter('nb_graphics_news.pagination.max_per_page');

        $countArticles = $em->getRepository(Article::class)->countArticlesByState(
            $em->getRepository(State::class)->findOneByRole('PUBLISH')
        );

        $pagination = array(
            'page' => $page,
            'route' => 'article_list',
            'pages_count' => ceil($countArticles / $maxArticles),
            'route_params' => array()
        );

        $articles = $em->getRepository(Article::class)->listArticles(
            $em->getRepository(State::class)->findOneByRole('PUBLISH'),
            $page,
            $maxArticles
        );

        return $this->render('NBGraphicsNewsBundle:pages:list.html.twig', array(
            'articles' => $articles,
            'pagination' => $pagination
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/actualite/{slug}", name="article_view")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        if ($article->getState() == $em->getRepository(State::class)->findOneByRole('DEFAULT')) {
            throw $this->createNotFoundException("Article indisponible");
        }

        return $this->render('NBGraphicsNewsBundle:pages:view.html.twig', array(
            'article' => $article
        ));
    }

    public function menuTopAction(Request $request, $active)
    {
        return $this->render('NBGraphicsNewsBundle:pages:menu_top.html.twig', array(
            'active' => ($active ? 'active' : '')
        ));
    }
}
