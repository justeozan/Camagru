# Project variables
NAME = Camagru
DC = docker compose

# Paths
APP_DIR = ./app
CONF_DIR = ./config
SQL_DIR = ./sql
PUBLIC_DIR = ./public
DOCKER_DIR = ./docker

# Colors
RED = \033[0;31m
GREEN = \033[0;32m
YELLOW = \033[0;33m
BLUE = \033[0;34m
PURPLE = \033[0;35m
CYAN = \033[0;36m
WHITE = \033[0;37m
RESET = \033[0m

# Message templates
SUCCESS = $(GREEN)✓$(RESET)
ERROR = $(RED)✗$(RESET)
INFO = $(BLUE)ℹ$(RESET)
WARNING = $(YELLOW)⚠$(RESET)

help:
	$(call print_message,$(CYAN),Available commands:)
	@echo "  $(GREEN)all$(RESET)      - Build and start the application"
	@echo "  $(GREEN)up$(RESET)       - Start the application"
	@echo "  $(GREEN)down$(RESET)     - Stop the application"
	@echo "  $(GREEN)build$(RESET)    - Build Docker containers"
	@echo "  $(GREEN)init-db$(RESET)  - Initialize database schema"
	@echo "  $(GREEN)logs$(RESET)     - Show container logs"
	@echo "  $(GREEN)clean$(RESET)    - Clean up containers and images"

all:
	@clear
	@make -j4 build -s
	@make -j4 up

up:
	@$(DC) up

down:
	@$(DC) down

build:
	@$(DC) build --no-cache

re:
	@make -j4 down
	@make -j4 all

init-db:
	@$(DC) exec app php $(SQL_DIR)/init.php -u${DB_USER} -p${DB_PASS}

clean:
	@$(DC) down -v --rmi all --remove-orphans