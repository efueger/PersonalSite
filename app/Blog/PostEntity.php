<?php

namespace App\Blog;

class PostEntity
{
    protected $id;
    protected $title;
    protected $slug;
    protected $content;
    protected $published;
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

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function getPublishedAt()
    {
        return $this->published_at;
    }
}
