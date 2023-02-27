FROM php:8.1-cli-alpine3.17

RUN set -eux; \
	apk add --no-cache --virtual .build-deps autoconf libzip-dev g++ make linux-headers openssh-client; \
    apk add --no-cache git unzip; \
    \
    pecl channel-update pecl.php.net; \
    \
    docker-php-ext-install sockets bcmath; \
    \
    apk del .build-deps;

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
