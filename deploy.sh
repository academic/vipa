#!/usr/bin/env bash
git pull origin master &&
rm -rf app/cache/* &&
rm -rf app/logs/* &&
composer update &&
php app/console cache:clear &&
php app/console assetic:dump