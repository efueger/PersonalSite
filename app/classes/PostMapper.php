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
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch();

        if ($post) {
            return new PostEntity($post);
        }

        return false;
    }

    public function save(PostEntity $post)
    {
        $query = "INSERT INTO posts (title, slug, content, published_at) VALUES (:title, :slug, :content, :published_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'title' => $post->getTitle(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'published_at' => $post->getPublishedAt()
        ]);
    }
}
