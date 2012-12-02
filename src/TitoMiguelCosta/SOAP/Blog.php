<?php
namespace TitoMiguelCosta\SOAP;

use Symfony\Component\DomCrawler\Crawler;
//define('PROJECT_ROOT', realpath(__DIR__.'/..'));

class Blog
{
    /**
     * Returns array with post entry details
     * 
     * @param string $slug
     * @return array
     */
    public function getPost($slug)
    {
        $data = array('aaa', 'bbc', 'aaa');
        
        $crawler = new Crawler();
        //$crawler->addXmlContent(file_get_contents(PROJECT_ROOT . '/data/xml/blog.xml'));
        //$post = $crawler->filterXPath('//post[@slug="' . $slug . '"]')->children();
        
//        if (count($post) == 1)
//        {
//            $data['title'] = $post->eq(0)->text();
//            $data['resume'] = $post->eq(1)->text();
//            $data['content'] = $post->eq(2)->text();
//            $data['date'] = $post->eq(4)->text();
//            $data['category'] = $post->eq(5)->text();
//            $data['tags'] = $post->eq(6)->text();
//        }
        
        return $data;
    }
}
