<?php

namespace App\Portfolio;

use App\Mapper;

class ProjectMapper extends Mapper
{
    public function getProjects()
    {
        $sql = "SELECT * FROM projects ORDER BY published_at DESC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new ProjectEntity($row);
        }

        return $results;
    }

    public function getProjectBySlug($slug)
    {
        $sql = "SELECT * FROM projects WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $project = $stmt->fetch();

        if ($project) {
            return new ProjectEntity($project);
        }

        return false;
    }

    public function save($project)
    {
        $query = "INSERT INTO projects (title, slug, description, live_url, github_url, technologies, preview, published_at) VALUES (:title, :slug, :description, :live_url, :github_url, :technologies, :preview, :published_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'title' => $project->getTitle(),
            'slug' => $project->getSlug(),
            'description' => $project->getDescription(),
            'live_url' => $project->getLiveUrl(),
            'github_url' => $project->getGithubUrl(),
            'technologies' => $project->getTechnologies(),
            'preview' => $project->getPreview(),
            'published_at' => $project->getPublishedAt()
        ]);
    }
}