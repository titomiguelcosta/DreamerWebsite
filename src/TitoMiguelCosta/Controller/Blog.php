<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null as NullIterator;
use Zend\Feed\Writer\Feed;
use ZendPdf\PdfDocument;
use ZendPdf\Page;
use ZendPdf\Font;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Client;
use TitoMiguelCosta\Parser\Blog as BlogParser;

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
        $max_per_page = 7;
        
        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        
        $total = $parser->totalPosts();
        $posts = $parser->getPosts(($page-1)*$max_per_page+1, $max_per_page*$page);
        
        /**
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        $total = $crawler->filterXPath('//post')->count();

        $posts = $crawler->filterXPath(sprintf('//post[position() >= %d and position() <= %d]', ($page-1)*$max_per_page+1, $max_per_page*$page));
        */
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
//        $crawler = new Crawler();
//        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
//        $posts = $crawler->filterXPath('//post[category[text()="'.$category.'"]]');
        
        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $posts = $parser->getPostsByCategory($category);
        
        return $app['twig']->render(
            'blog/list.twig',
            array('posts' => $posts, 'category' => $category, 'pages' => false)
        );
    }

    public function postAction(Application $app, $slug)
    {
//        $crawler = new Crawler();
//        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        
        $post = $parser->getPostBySlug($slug);
        $posts = $parser->getPosts();
        
        return $app['twig']->render('blog/post.twig', array(
                    'post' => $post,
                    'posts' => $posts,
                    'slug' => $slug
                ));
    }
    public function pdfAction(Application $app, $slug)
    {
//        $crawler = new Crawler();
//        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
//
//        $post = $crawler->filterXPath('//post[@slug="' . $slug . '"]')->children();
        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $post = $parser->getPostBySlug($slug);
        
        $pdf = new PdfDocument();
        $page = $pdf->newPage(Page::SIZE_A4);
        $page
            ->setFont(Font::fontWithName(Font::FONT_HELVETICA), 16)
            ->drawText($post->getTitle(), 10, $page->getHeight() - 20);

        $pdf->pages[] = $page;

//        for the record: the way Symfony2 does it to generate the Content-Disposition header
//        $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $slug.'.pdf');
//        $response->headers->set('Content-Disposition', $d);
        $headers = array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $slug)
        );

        $response = new Response($pdf->render(), 200, $headers);
        $response->setTtl(86400); // 1 day

        return $response;
    }
    public function feedAction(Application $app)
    {
//        $crawler = new Crawler();
//        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
//
//        $posts = $crawler->filterXPath('//post[position() >= 1 and position() <= 10]');

        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $posts = $parser->getPosts(1, 10);
        
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
        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($app['url_generator']->generate('blog_post', array('slug' => $post->getSlug()), true));
            $entry->addAuthor(array(
                'name'  => 'Tito Miguel Costa',
                'email' => 'website@titomiguelcosta.com',
                'uri'   => 'http://www.titomiguelcosta.com',
            ));
            $entry->setDateModified(time());
            $entry->setDateCreated(time());
            $entry->setDescription($post->getTitle());
            $entry->setContent($post->getContent());
            $feed->addEntry($entry);
        }
        $response = new Response($feed->export('atom'));
        $response->headers->set('content-type', 'application/atom+xml');

        return $response;
    }
    public function clientAction(Application $app, Request $request, $slug)
    {
        $soap = new Client($app['url_generator']->generate('blog_soap_wsdl',  array(), true));
        $post = array();
        try {
            $post = $soap->getPostBySlug($slug);
        } catch (\Exception $e) {
        }

        return new Response(print_r($post, true), 200, array('Content-Type' => 'text/txt'));
    }
    public function serverAction(Application $app, Request $request)
    {
        $soap = new Server($app['url_generator']->generate('blog_soap_wsdl',  array(), true));
        $soap->setClass('\TitoMiguelCosta\SOAP\Blog');

        $response = new Response();

        ob_start();
        $soap->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }
    public function wsdlAction(Application $app)
    {
        $soap = new AutoDiscover();
        $soap->setClass('\TitoMiguelCosta\SOAP\Blog');
        $soap->setUri($app['url_generator']->generate('blog_soap_server',  array(), true));
        $soap->setServiceName('TitoMiguelCostaBlog');
        $xml = $soap->toXml();

        $response = new Response();
        $response->setContent($xml);
        $response->headers->set('Content-Type', 'text/xml');
        $response->setTtl(86400);

        return $response;
    }
}
