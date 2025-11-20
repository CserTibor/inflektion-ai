.SILENT:
.PHONY: help

include .env

COMPOSE=docker compose
DOCKER_EXEC=docker exec -it
APP_SERVICE=inflektion-api

# ===================================================
# Setup: copy .env, build & start containers
# ===================================================
setup:
	@echo "Copying .env.example to .env..."
	@cp -n .env.example .env || echo ".env already exists"
	@echo "Building and starting Docker containers..."
	$(COMPOSE) up -d
	@echo "Running composer install in $(APP_SERVICE) container..."
	$(DOCKER_EXEC) $(APP_SERVICE) composer install
	@echo "Create app key $(APP_SERVICE) container..."
	$(DOCKER_EXEC) $(APP_SERVICE) php artisan key:generate

# ===================================================
# Up: start containers
# ===================================================
up:
	@echo "Starting Docker containers..."
	$(COMPOSE) up -d

# ===================================================
# In: ssh into app container
# ===================================================
cli:
	@echo "Opening shell into $(APP_SERVICE) container..."
	$(DOCKER_EXEC) -u application $(APP_SERVICE) bash
