<?php

namespace TitoMiguelCosta\Controller;

use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\NullFill as NullIterator;
use Zend\Feed\Writer\Feed;
use ZendPdf\PdfDocument;
use ZendPdf\Page;
use ZendPdf\Font;
use TitoMiguelCosta\Parser\Blog as BlogParser;

class Blog
{
    public function listAction(Application $app, $page = 1)
    {
        $page = (int) ($page > 1 ? $page : 1);
        $maxPerPage = 7;

        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));

        $total = $parser->totalPosts();
        $posts = $parser->getPosts(($page - 1) * $maxPerPage + 1, $maxPerPage * $page);

        $paginator = new Paginator(new NullIterator($total));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($maxPerPage);

        return $app['twig']->render(
            'blog/list.twig',
            array('posts' => $posts, 'pages' => $paginator->getPages(), 'route' => 'blog_list')
        );
    }

    public function categoryAction(Application $app, $category)
    {
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
        $parser = new BlogParser();
        $parser->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        $posts = $parser->getPosts(1, 10);

        $feed = new Feed();
        $feed->setTitle('Tito Miguel Costa Blog');
        $feed->setLink('https://www.titomiguelcosta.com');
        $feed->setFeedLink($app['url_generator']->generate('blog_feed', array(), true), 'atom');
        $feed->addAuthor(array(
            'name'  => 'Tito Miguel Costa',
            'email' => 'website@titomiguelcosta.com',
            'uri'   => 'https://www.titomiguelcosta.com',
        ));
        $feed->setDateModified(time());
        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($app['url_generator']->generate('blog_post', array('slug' => $post->getSlug()), true));
            $entry->addAuthor(array(
                'name'  => 'Tito Miguel Costa',
                'email' => 'website@titomiguelcosta.com',
                'uri'   => 'https://www.titomiguelcosta.com',
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
}
