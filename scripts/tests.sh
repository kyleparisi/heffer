#!/usr/bin/env bash

docker run --rm -v $(pwd):/app composer/composer install &&
docker exec heffer /bin/bash -c 'cd /var/www/html/tests && ./phpunit .' || echo "Make sure the heffer container is running" && exit 1