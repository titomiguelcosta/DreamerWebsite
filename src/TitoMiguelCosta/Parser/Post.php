<?php

namespace TitoMiguelCosta\Parser;

class Post
{
    protected $title, $resume, $content, $date, $image, $category, $tags, $slug;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getResume()
    {
        return $this->resume;
    }

    public function setResume($resume)
    {
        $this->resume = $resume;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public static function getNodes()
    {
        return array(
            'title',
            'resume',
            'content',
            'date',
            'image',
            'category',
            'tags'
        );
    }

    public static function getPostFromArray(array $array)
    {
        $post = new Post();
        $post->setTitle($array['title']);
        $post->setResume($array['resume']);
        $post->setContent($array['content']);
        $post->setDate($array['date']);
        $post->setImage($array['image']);
        $post->setCategory($array['category']);
        $post->setTags($array['tags']);
        
        return $post;
    }

    public static function getPostFromRawData(\DOMElement $rawPost)
    {
        $post = new Post();
        $post->setTitle($rawPost->getElementsByTagName('title')->item(0)->nodeValue);
        $post->setResume($rawPost->getElementsByTagName('resume')->item(0)->nodeValue);
        $post->setContent($rawPost->getElementsByTagName('content')->item(0)->nodeValue);
        $post->setImage($rawPost->getElementsByTagName('image')->item(0)->nodeValue);
        $post->setDate($rawPost->getElementsByTagName('date')->item(0)->nodeValue);
        $post->setCategory($rawPost->getElementsByTagName('category')->item(0)->nodeValue);
        $post->setTags($rawPost->getElementsByTagName('tags')->item(0)->nodeValue);
        $post->setSlug($rawPost->getAttribute('slug'));

        return $post;
    }

}
