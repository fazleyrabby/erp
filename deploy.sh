#!/usr/bin/env bash

# Exit immediately if a command exits with a non-zero status
set -e

# Configuration
REMOTE_HOST="homelab"
REMOTE_DIR="/home/fazley/apps/erp"
BRANCH="main"

echo "=================================================="
echo "🚀 Starting Deployment to Homelab ($REMOTE_HOST) via Git"
echo "=================================================="

# 1. Ensure local changes are pushed to GitHub
echo "👉 1/3 Checking local branch & pushing changes..."

# Verify we are on the right branch locally
LOCAL_BRANCH=$(git rev-parse --abbrev-ref HEAD)
if [ "$LOCAL_BRANCH" != "$BRANCH" ]; then
  echo "❌ Error: You are on branch '$LOCAL_BRANCH', but the deploy script is configured for '$BRANCH'."
  exit 1
fi

# Check for uncommitted changes (ignoring deploy.sh itself)
if [ -n "$(git status --porcelain | grep -v 'deploy.sh')" ]; then
  echo "❌ Error: You have uncommitted local changes. Please commit them before deploying."
  exit 1
fi

echo "Pushing local commits to GitHub..."
git push origin "$BRANCH"

# 2. Pull changes on remote homelab
echo "👉 2/3 Pulling latest changes on homelab..."
ssh "$REMOTE_HOST" "cd $REMOTE_DIR && \
  if [ ! -d .git ]; then
    echo 'Initializing Git repo on homelab...' && \
    git init && \
    git remote add origin git@github.com:mdhamidulislamT/jtsc-service-centre.git && \
    git fetch origin $BRANCH && \
    git checkout -f -B $BRANCH origin/$BRANCH && \
    git branch --set-upstream-to=origin/$BRANCH $BRANCH;
  fi && \
  git pull origin $BRANCH"

# 3. Build and restart Docker services, run migrations & clear cache
echo "👉 3/3 Rebuilding remote container and clearing cache..."
ssh "$REMOTE_HOST" "cd $REMOTE_DIR && \
  docker compose -f docker-compose.prod.yml build app && \
  docker compose -f docker-compose.prod.yml up -d --remove-orphans && \
  docker exec erp_app php artisan migrate --force && \
  docker exec erp_app php artisan config:clear && \
  docker exec erp_app php artisan cache:clear && \
  docker exec erp_app php artisan view:clear"

echo "=================================================="
echo "✨ Deployment completed successfully!"
echo "=================================================="
