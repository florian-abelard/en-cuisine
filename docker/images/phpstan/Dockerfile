FROM ghcr.io/phpstan/phpstan:latest-php8.2

RUN composer global config --no-plugins allow-plugins.phpstan/extension-installer true \
    && composer global require phpstan/extension-installer \
    && composer global require phpstan/phpstan-symfony \
    && composer global require phpstan/phpstan-doctrine
