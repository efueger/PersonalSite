<?php

class PostMapper extends Mapper
{
    public function getPosts()
    {
        $sql = "SELECT * FROM posts ORDER BY published_at DESC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new PostEntity($row);
        }

        return $results;
    }

    public function getPostBySlug($slug)
    {
        $sql = "SELECT * FROM posts WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['slug' => $slug]);

        if ($result) {
            return new PostEntity($stmt->fetch());
        }
    }
}
