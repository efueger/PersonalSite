<?php

class PostMapper extends Mapper
{
    public function getPosts() {
        $sql = "SELECT * FROM posts";
        $stmt = $this->db->query($sql);

        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new PostEntity($row);
        }

        return $results;
    }
}
