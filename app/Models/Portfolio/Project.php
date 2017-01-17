<?php

namespace App\Models\Portfolio;

class Project
{
    protected $id;
    protected $title;
    protected $slug;
    protected $description;
    protected $published_at;
    protected $published;
    protected $live_url;
    protected $github_url;
    protected $technologies;
    protected $preview;

    public function __construct(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        $this->title = $data['title'];
        $this->slug = $data['slug'];
        $this->description = $data['description'];
        $this->published_at = $data['published_at'];
        $this->live_url = $data['live_url'];
        $this->github_url = $data['github_url'];
        $this->technologies = $data['technologies'];
        $this->preview = $data['preview'];
        $this->published = true;

        if (is_null($this->published_at) || $this->published_at > date("Y-m-d H:i:s", time())) {
            $this->published = false;
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

    public function getDescription()
    {
        return $this->description;
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

    public function getLiveUrl()
    {
        return $this->live_url;
    }

    public function getGithubUrl()
    {
        return $this->github_url;
    }

    public function getTechnologies()
    {
        return $this->technologies;
    }

    public function getPreview()
    {
        return $this->preview;
    }
}
