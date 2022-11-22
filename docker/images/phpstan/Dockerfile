FROM ghcr.io/phpstan/phpstan:latest-php8.1

RUN composer global config --no-plugins allow-plugins.phpstan/extension-installer true \
    && composer global require phpstan/extension-installer \
    && composer global require phpstan/phpstan-symfony
