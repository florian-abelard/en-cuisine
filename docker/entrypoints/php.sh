#!/bin/sh

composer install --no-progress --prefer-dist --optimize-autoloader

chown -R `stat -c "%u:%g" composer.json` ./var/
chown -R `stat -c "%u:%g" composer.json` ./vendor/

php-fpm