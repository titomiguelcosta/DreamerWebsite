<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Silex\Application;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Blog
{
    public function listAction(Application $app, $page = 1)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $posts = $crawler->filterXPath('//post');

        return $app['twig']->render(
            'blog/list.twig',
            array('posts' => $posts)
        );
    }
    public function categoryAction(Application $app, $category)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $posts = $crawler->filterXPath("//post[category='".$category."']");

        return $app['twig']->render(
            'blog/list.twig',
            array('posts' => $posts, 'category' => $category)
        );
    }

    public function postAction(Application $app, $slug)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        return $app['twig']->render('blog/post.twig', array(
                    'post' => $crawler->filterXPath('//post[@slug="' . $slug . '"]')->children(),
                    'posts' => $crawler->filterXPath('//post')
                ));
    }
}
