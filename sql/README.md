# Database Setup

This directory contains the database schema for the Camagru application using MariaDB.

## Files

- `schema.sql` - Complete database schema with all tables
- `init.php` - PHP script to initialize the database

## Usage

To initialize the database schema:

```bash
# Using the Makefile (with Docker)
make init-db

# Or directly with PHP (inside Docker container)
docker compose exec camagru_app php sql/init.php

# Or manually with MariaDB
docker compose exec mariadb mysql -u root -p camagru < sql/schema.sql
```

## Schema Overview

The database contains the following tables:

- **users** - User accounts with username, email, and password
- **posts** - User posts with images and captions
- **comments** - Comments on posts
- **likes** - Likes on posts

All tables include proper foreign key constraints and timestamps.
