#!/usr/bin/env bash

if [ ! "$ENV" ]; then
  echo "You did not specifiy what environment to run.  ENV=[development,production]"  && exit 1
fi

docker --version || { echo >&2 "You need to install docker."; exit 1; }
if [ ! -d "$HOME/.aws" ]; then
  echo "You need to configure aws cli." && exit 1
fi

docker build . -t kyleparisi/heffer

if [ "$ENV" == "development" ]; then
    docker run --rm -v $(pwd):/app composer/composer install
    docker run --rm --name heffer -p 3000:80 -e "ENV="$ENV -v $(pwd):/var/www/html/ heffer
fi

if [ "$ENV" == "production" ]; then
    docker run --rm -v $(pwd):/app composer/composer install --no-dev
    docker run --rm --name heffer -p 3000:80 -e "ENV="$ENV -v $(pwd):/var/www/html/ -v ~/.aws/:/.aws/ heffer
fi
