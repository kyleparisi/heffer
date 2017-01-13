#!/usr/bin/env bash

docker run --rm -v $(pwd):/app composer/composer "$@"
