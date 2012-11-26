<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Silex\Application;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null as NullIterator;
use Zend\Feed\Writer\Feed;
use Zend\Feed\Writer\Entry;

/**
 * Description of Controller
 *
 * @author titomiguelcosta
 */
class Blog
{
    public function listAction(Application $app, $page = 1)
    {
        $page = (int) ($page > 1 ? $page : 1);
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        $max_per_page = 7;
        $total = $crawler->filterXPath('//post')->count();

        $posts = $crawler->filterXPath(sprintf('//post[position() >= %d and position() <= %d]', ($page-1)*$max_per_page+1, $max_per_page*$page));

        $paginator = new Paginator(new NullIterator($total));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($max_per_page);

        return $app['twig']->render(
            'blog/list.twig',
            array('posts' => $posts, 'pages' => $paginator->getPages(), 'route' => 'blog_list')
        );
    }
    public function categoryAction(Application $app, $category)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $posts = $crawler->filterXPath('//post[category[text()="'.$category.'"]]');

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
                    'posts' => $crawler->filterXPath('//post'),
                    'slug' => $slug
                ));
    }
    public function feedAction(Application $app)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        $posts = $crawler->filterXPath('//post[position() >= 1 and position() <= 10]');

        $feed = new Feed();
        $feed->setTitle('Tito Miguel Costa Blog');
        $feed->setLink('http://www.titomiguelcosta.com');
        $feed->setFeedLink($app['url_generator']->generate('blog_feed', array(), true), 'atom');
        $feed->addAuthor(array(
            'name'  => 'Tito Miguel Costa',
            'email' => 'website@titomiguelcosta.com',
            'uri'   => 'http://www.titomiguelcosta.com',
        ));
        $feed->setDateModified(time());
        foreach ($posts as $post)
        {
            $entry = $feed->createEntry();
            $entry->setTitle('All Your Base Are Belong To Us');
            $entry->setLink($app['url_generator']->generate('blog_post', array('slug' => $post->getAttribute('slug')), true));
            $entry->addAuthor(array(
                'name'  => 'Tito Miguel Costa',
                'email' => 'website@titomiguelcosta.com',
                'uri'   => 'http://www.titomiguelcosta.com',
            ));
            $entry->setDateModified(time());
            $entry->setDateCreated(time());
            $entry->setDescription($post->getElementsByTagName('title')->item(0)->nodeValue);
            $entry->setContent($post->getElementsByTagName('content')->item(0)->nodeValue);
            $feed->addEntry($entry);
        }
        $response = new Response($feed->export('atom'));
        $response->headers->set('content-type', 'application/atom+xml');

        return $response;
    }
}
