<?php

namespace App\Models\Blog;

use App\Models\BaseMapper;

class PostMapper extends BaseMapper
{
    public function getPosts()
    {
        $sql = "SELECT * FROM posts ORDER BY published_at DESC";
        $stmt = $this->db->query($sql);

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new Post($row);
        }

        return $results;
    }

    public function getPostBySlug($slug)
    {
        $sql = "SELECT * FROM posts WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch();

        if ($post) {
            return new Post($post);
        }

        return false;
    }

    public function save(Post $post)
    {
        $query = "INSERT INTO posts (title, slug, content, published_at) 
            VALUES (:title, :slug, :content, :published_at)"
        ;
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'published_at' => $post->getPublishedAt()
        ]);
    }

    public function countPublishedPosts()
    {
        $sql = "SELECT COUNT(*) AS total FROM posts WHERE published_at IS NOT NULL LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch();

        return $count['total'];
    }

    public function countDraftPosts()
    {
        $sql = "SELECT COUNT(*) AS total FROM posts WHERE published_at IS NULL LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch();

        return $count['total'];
    }

    public function getPublishedPosts()
    {
        $sql = "SELECT * FROM posts WHERE published_at IS NOT NULL ORDER BY published_at DESC";
        $stmt = $this->db->query($sql);

        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = new Post($row);
        }

        return $posts;
    }

    public function delete($slug)
    {
        $sql = "DELETE FROM posts WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);

        return true;
    }
}
