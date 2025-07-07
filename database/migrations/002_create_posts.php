<?php

class CreatePosts extends Migration {
	public function up() {
		$sql = "CREATE TABLE IF NOT EXISTS posts (
			id INT AUTO_INCREMENT PRIMARY KEY,
			user_id INT NOT NULL,
			image_path VARCHAR(255) NOT NULL,
			caption TEXT,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
		)";
		$this->pdo->exec($sql);
	}

	public function down() {
		$sql = "DROP TABLE IF EXISTS posts";
		$this->pdo->exec($sql);
	}
}