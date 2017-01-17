<?php

namespace App\Models\Blog;

/**
 * Class Post
 * @package App\Models\Blog
 */
class Post
{
    /**
     * @var mixed
     */
    protected $id;
    /**
     * @var mixed
     */
    protected $title;
    /**
     * @var mixed
     */
    protected $slug;
    /**
     * @var mixed
     */
    protected $content;
    /**
     * @var bool
     */
    protected $published;
    /**
     * @var mixed
     */
    protected $publishedAt;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data)
    {
        // no id if we're creating
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        $this->title = $data['title'];
        $this->slug = $data['slug'];
        $this->content = $data['content'];
        $this->publishedAt = $data['published_at'];
        $this->published = true;

        if ($this->publishedAt == null || $this->publishedAt > date("Y-m-d H:i:s", time())) {
            $this->published = false;
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return mixed
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param $publishedAt
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }
}
