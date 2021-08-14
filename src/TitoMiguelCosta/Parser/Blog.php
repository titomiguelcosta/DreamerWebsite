<?php

namespace TitoMiguelCosta\Parser;

use Symfony\Component\DomCrawler\Crawler;

class Blog extends Crawler
{
    const MAX_PER_PAGE = 7;

    public function getPosts(int $start = null, int $end = null): array
    {
        $total = $this->totalPosts();

        $start = null === $start || $start < 1 ? 1 : $start;
        $end = null === $end || $end < 1 || $end > $total ? self::MAX_PER_PAGE : $end;

        $rawPosts = $this->filterXPath(sprintf('//post[position() >= %d and position() <= %d]', $start, $end));

        $posts = [];
        foreach ($rawPosts as $rawPost) {
            $posts[] = Post::getPostFromRawData($rawPost);
        }

        return $posts;
    }

    public function getPostsByCategory($category): array
    {
        $rawPosts = $this->filterXPath('//post[category[text()="' . $category . '"]]');

        $posts = [];
        foreach ($rawPosts as $domElement) {
            $posts[] = Post::getPostFromRawData($domElement);
        }

        return $posts;
    }

    public function getPostBySlug($slug): Post
    {
        $rawPost = $this->filterXPath('//post[@slug="' . $slug . '"]');

        return $rawPost->getPost();
    }

    public function totalPosts(): int
    {
        return (int) $this->filterXPath('//post')->count();
    }

    protected function getPost(): Post
    {
        $array = [];
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
