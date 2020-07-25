<?php

namespace TitoMiguelCosta\SOAP;

use Symfony\Component\DomCrawler\Crawler;

class Blog
{
    protected $root_dir;

    public function __construct()
    {
        $this->root_dir = realpath(__DIR__ . '/../../../data/xml/blog.xml');
    }

    /**
     * Returns array with post entry details
     *
     * @param  string $slug
     * @return array
     */
    public function getPost($slug)
    {
        $data = array();

        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents($this->root_dir));
        try {
            $post = $crawler->filterXPath('//post[@slug="' . $slug . '"]')->children();
            $data['slug'] = $slug;
            $data['title'] = $post->eq(0)->text();
            $data['resume'] = $post->eq(1)->text();
            $data['content'] = $post->eq(2)->text();
            $data['data'] = $post->eq(4)->text();
            $data['category'] = $post->eq(5)->text();
            $data['tags'] = $post->eq(6)->text();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $data;
    }
}
