<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;
use Silex\Application;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Null as NullIterator;
use Zend\Feed\Writer\Feed;
use ZendPdf\PdfDocument;
use ZendPdf\Page;
use ZendPdf\Font;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Client;
use TitoMiguelCosta\SOAP\Blog as SoapBlog;

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
    public function pdfAction(Application $app, $slug)
    {
        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        $post = $crawler->filterXPath('//post[@slug="' . $slug . '"]')->children();
        $pdf = new PdfDocument();
        $page = $pdf->newPage(Page::SIZE_A4);
        $page
            ->setFont(Font::fontWithName(Font::FONT_HELVETICA), 16)
            ->drawText($post->eq(0)->text(), 10, $page->getHeight() - 20);
        
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
            $entry->setTitle($post->getElementsByTagName('title')->item(0)->nodeValue);
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
    public function clientAction(Application $app, Request $request, $slug)
    {
        //$soap = new Client($app['url_generator']->generate('blog_soap_wsdl',  array(), true));
        $soap = new Client($request->getBasePath().'/soap/wsdl.xml');
        
        $post = $soap->getPost($slug);
        
        return new Response(print_r($post, true), 200, array('Content-Type' => 'text/txt'));
    }
    public function serverAction(Application $app, Request $request)
    {
        //$soap = new Server($app['url_generator']->generate('blog_soap_wsdl',  array(), true));
        $soap = new Server($request->getBasePath().'/soap/wsdl.xml');
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
        //$soap->setServiceName('TitoMiguelCostaBlog');
        $response = new Response();
        $response->setContent($soap->toXml());

        return $response;
    }
}