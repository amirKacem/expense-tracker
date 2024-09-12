#!/bin/bash

bin/docker-compose exec app  sh -c  "php bin/console.php ${1}"