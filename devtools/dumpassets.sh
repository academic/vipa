#!/usr/bin/env bash
php app/console assets:install web --symlink
php app/console assetic:dump 
