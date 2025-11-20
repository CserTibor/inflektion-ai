FROM webdevops/php-nginx:8.2
ARG version
ENV WEB_DOCUMENT_ROOT="/app/public"

RUN echo date.timezone = "Europe/Budapest" >> /opt/docker/etc/php/php.ini

RUN apt-get update && apt-get install -y dos2unix mc

WORKDIR "/app"
COPY . /app

COPY prod-docker/migrate-and-seed.sh /entrypoint.d/migrate-and-seed.sh
RUN dos2unix /entrypoint.d/migrate-and-seed.sh
COPY prod-docker/php-ini-overrides.ini /opt/docker/etc/php/php.ini
COPY prod-docker/laravel-worker.conf /opt/docker/etc/supervisor.d/laravel-worker.conf
COPY prod-docker/nginx.conf /opt/docker/etc/nginx/vhost-common.d/nginx-prod.conf

RUN composer install --no-ansi --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader

RUN crontab -u application /app/prod-docker/cron
RUN chown -R application:application /app

EXPOSE 80
