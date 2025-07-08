<?php

class Like extends Model 
{
    public function create($postId, $userId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO likes (post_id, user_id) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$postId, $userId]);
    }

    public function delete($postId, $userId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM likes 
            WHERE post_id = ? AND user_id = ?
        ");
        return $stmt->execute([$postId, $userId]);
    }

    public function exists($postId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM likes 
            WHERE post_id = ? AND user_id = ?
        ");
        $stmt->execute([$postId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function countByPostId($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetchColumn();
    }
} 