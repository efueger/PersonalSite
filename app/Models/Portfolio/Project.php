<?php

namespace App\Models\Portfolio;

class Project
{
    protected $id;
    protected $title;
    protected $slug;
    protected $description;
    protected $publishedAt;
    protected $published;
    protected $liveUrl;
    protected $githubUrl;
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
        $this->publishedAt = $data['published_at'];
        $this->liveUrl = $data['live_url'];
        $this->githubUrl = $data['github_url'];
        $this->technologies = $data['technologies'];
        $this->preview = $data['preview'];
        $this->published = true;

        if (is_null($this->publishedAt) || $this->publishedAt > date("Y-m-d H:i:s", time())) {
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
        return $this->publishedAt;
    }

    public function getLiveUrl()
    {
        return $this->liveUrl;
    }

    public function getGithubUrl()
    {
        return $this->githubUrl;
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
