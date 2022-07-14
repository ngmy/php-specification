FROM php:8-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 docker
RUN adduser -u 1000 -G docker -D docker

USER docker

ENV COMPOSER_NO_INTERACTION 1

RUN composer global config allow-plugins.bamarni/composer-bin-plugin true
RUN composer global require bamarni/composer-bin-plugin

ENV PATH /var/www/vendor/bin:$PATH
