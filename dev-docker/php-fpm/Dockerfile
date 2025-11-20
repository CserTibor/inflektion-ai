FROM webdevops/php-nginx-dev:8.2

RUN echo date.timezone = "Europe/Budapest" >> /opt/docker/etc/php/php.ini

RUN apt-get update

WORKDIR "/app"
COPY . /app

RUN chown -R application:application /app

RUN git config --global --add safe.directory /app
