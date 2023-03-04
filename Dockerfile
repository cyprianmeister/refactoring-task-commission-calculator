FROM php:8.1-cli-alpine3.17

RUN set -eux; \
	apk add --no-cache --virtual .build-deps autoconf libzip-dev g++ make linux-headers openssh-client; \
    apk add --no-cache git unzip; \
    \
    pecl channel-update pecl.php.net; \
    \
    docker-php-ext-install sockets bcmath; \
    \
    apk del .build-deps; \
    \
    cd /usr/local/etc/php/conf.d/; \
    { \
    echo 'date.timezone = Europe/Warsaw'; \
    } | tee 00_timezone.ini; \
    \
    cd /usr/local/etc/php/conf.d/; \
    { \
    echo 'short_open_tag = Off'; \
    echo 'session.auto_start = Off'; \
    echo 'magic_quotes_gpc = Off'; \
    echo 'register_globals = Off'; \
    echo 'memory_limit = 256M'; \
    echo 'realpath_cache_size = 4096K'; \
    echo 'realpath_cache_ttl = 600'; \
    echo 'expose_php = off'; \
    } | tee 00_settings.ini; \
    \
    docker-php-ext-install opcache; \
    cd /usr/local/etc/php/conf.d/; \
    { \
    echo 'opcache.enable = 1'; \
    echo 'opcache.enable_cli = 1'; \
    echo 'opcache.fast_shutdown = 1'; \
    echo 'opcache.validate_timestamps = 0'; \
    echo 'opcache.max_wasted_percentage = 10'; \
    echo 'opcache.memory_consumption = 128'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.interned_strings_buffer = 8'; \
    echo 'opcache.jit_buffer_size = 100M'; \
    echo 'opcache.jit = 1255'; \
    } | tee 00_opcache.ini;

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

ARG APP_PATH=/var/app/
ARG USER_ID=1000
ARG USER_GID=1000
ARG USER_NAME=runner

RUN addgroup --gid ${USER_GID} --system ${USER_NAME}; \
    adduser --uid ${USER_ID} --system --ingroup ${USER_NAME} --home /home/${USER_NAME} --shell /bin/bash --disabled-password --gecos "" ${USER_NAME}

RUN mkdir ${APP_PATH}
RUN chown -R ${USER_NAME}:${USER_NAME} ${APP_PATH}

USER ${USER_NAME}

WORKDIR ${APP_PATH}
