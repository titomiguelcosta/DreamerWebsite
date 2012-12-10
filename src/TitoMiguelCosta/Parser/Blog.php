<?php

namespace TitoMiguelCosta\Parser;

use Symfony\Component\DomCrawler\Crawler;

class Blog extends Crawler
{

    public function getPosts($start = null, $end = null)
    {
        $total = $this->totalPosts();

        $start = null === $start || $start < 1 ? 1 : $start;
        $end = null === $end || $end < 1 || $end > $total ? $total : $end;

        $rawPosts = $this->filterXPath(sprintf('//post[position() >= %d and position() <= %d]', $start, $end));

        $posts = array();
        foreach ($rawPosts as $rawPost) {
            $posts[] = Post::getPostFromRawData($rawPost);
        }

        return $posts;
    }

    public function getPostsByCategory($category)
    {
        $rawPosts = $this->filterXPath('//post[category[text()="' . $category . '"]]');

        $posts = array();
        foreach ($rawPosts as $domElement) {
            $posts[] = Post::getPostFromRawData($domElement);
        }

        return $posts;
    }

    public function getPostBySlug($slug)
    {
        $rawPost = $this->filterXPath('//post[@slug="' . $slug . '"]');

        return $rawPost->getPost();
    }

    public function totalPosts()
    {
        return (int) $this->filterXPath('//post')->count();
    }

    protected function getPost()
    {
        $array = array();
        $attributes = Post::getNodes();
        foreach ($this->children() as $node) {
            $tag = $node->tagName;

            if (in_array($tag, $attributes)) {
                $array[$tag] = $node->nodeValue;
            }
        }

        $post = Post::getPostFromArray($array);
        $post->setSlug($this->attr('slug'));
        
        return $post;
    }

}