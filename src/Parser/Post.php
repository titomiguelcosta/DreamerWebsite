<?php

namespace TitoMiguelCosta\Parser;

use DateTimeInterface;

class Post
{
    protected $title, $resume, $content, $date, $image, $category, $tags, $slug;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): void
    {
        $this->resume = $resume;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public static function getNodes(): array
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

    public static function getPostFromArray(array $array): Post
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

    public static function getPostFromRawData(\DOMElement $rawPost): Post
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
