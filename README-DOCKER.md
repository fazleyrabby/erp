# Docker Setup for Laravel ERP (Production)

Self-contained, production-ready Docker setup (no Laravel Sail). Modeled after the LitePOS project.

## Files

- `Dockerfile` — `php:8.2-fpm` image with nginx + php-fpm + supervisord, Redis extension, Composer, and a `npm run production` asset build (Laravel Mix).
- `docker/nginx.conf` — nginx vhost (root `/var/www/html/public`, `try_files`, security headers, gzip, static caching).
- `docker/supervisord.conf` — runs php-fpm + nginx in one container.
- `docker/php-local.ini` — PHP prod config (memory, upload limits, opcache, error logging).
- `docker-compose.yml` — `app` (built from Dockerfile) + `mysql:8.0` + `redis:7-alpine`.
- `.dockerignore` — keeps the build context clean.

## Deploy

```bash
# 1. Configure .env (DB_HOST will be overridden to "mysql", REDIS_HOST to "redis")
#    Make sure DB_PASSWORD and DB_ROOT_PASSWORD are set.

# 2. Build & start
docker compose build
docker compose up -d

# 3. App containers serve on http://localhost:8080
#    MySQL on 3306, Redis on 6379 (change ports in compose if needed)

# 4. Run migrations / seed (one-time / on deploy)
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
```

## Notes

- The image runs `composer install --no-dev`, so `laravel/sail` (a dev dependency) is NOT installed in production.
- `storage/` and `bootstrap/cache/` are bind-mounted; ensure the host directories are writable by the container's `www-data` user, or adjust ownership on the server (`chown -R 33:33 storage bootstrap/cache`).
- Asset compilation happens at build time (`npm run production`), so no Node is needed on the host/VPS.
- For zero-downtime or multi-instance deploys, put a reverse proxy (Caddy/Nginx/Traefik) in front and point it at `erp_app:80`.
