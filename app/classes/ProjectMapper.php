<?php

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
        $result = $stmt->execute(['slug' => $slug]);

        if ($result) {
            return new ProjectEntity($stmt->fetch());
        }
    }
}