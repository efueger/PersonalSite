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
    protected $published_at;

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
        $this->published_at = $data['published_at'];

        if ($this->published_at == null || $this->published_at > date("Y-m-d H:i:s", time())) {
            $this->published = false;
        } else {
            $this->published = true;
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
        return $this->published_at;
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
        $this->published_at = $publishedAt;
    }
}
