#!/bin/bash
# Deploy hooks for seekrecruit
# Called by ~/deploy/deploy.sh after git pull + docker compose up -d

set -euo pipefail

CONTAINER="seekrecruit"

echo "Running seekrecruit deploy hooks..."

# Wait for container to be healthy
echo "Waiting for container to be ready..."
timeout 60 bash -c "until docker exec $CONTAINER php /var/www/html/artisan --version > /dev/null 2>&1; do sleep 2; done"

# Run migrations
echo "Running migrations..."
docker exec $CONTAINER gosu sail php /var/www/html/artisan migrate --force

# Clear + rebuild caches
echo "Clearing caches..."
docker exec $CONTAINER gosu sail php /var/www/html/artisan config:cache
docker exec $CONTAINER gosu sail php /var/www/html/artisan route:cache
docker exec $CONTAINER gosu sail php /var/www/html/artisan view:cache

echo "SeekRecruit deploy hooks complete."
