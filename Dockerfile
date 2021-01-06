FROM php:8.0.0-buster

ENV DOMINOES_APP_DIR=/usr/src/php-dominoes

WORKDIR "$DOMINOES_APP_DIR"

RUN DEBIAN_FRONTEND=noninteractive apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y git zip unzip && \
    rm -rfv "$DOMINOES_APP_DIR/*" && \
    curl -sS -o "/tmp/composer.phar" https://getcomposer.org/download/2.0.7/composer.phar && \
    ln -s "/tmp/composer.phar" "$DOMINOES_APP_DIR/composer.phar"

COPY ./ "$DOMINOES_APP_DIR/"

RUN php composer.phar install --no-progress

CMD ["php", "./public/index.php" ]
