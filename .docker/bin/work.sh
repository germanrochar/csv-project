#!/bin/sh

sleep 5

php artisan queue:work --memory=64 --delay=1 --sleep=1 --tries=5
