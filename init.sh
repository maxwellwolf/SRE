#!/bin/bash

docker rm db --force 2> /dev/null
docker rm www --force 2> /dev/null
docker network rm SRE 2> /dev/null
docker volume remove db_volume 2> /dev/null
docker rmi php_apache 2> /dev/null
docker rmi db_mysql 2> /dev/null

docker network create SRE

docker volume create db_volume

docker build -t maxwellwolf/apache:1.0 ./apache/

docker build -t maxwellwolf/mysql:1.0 ./mysql/

docker run --name db --network=SRE -v db_volume:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=987654321 -e MYSQL_PASSWORD=admin -p 3306:3306 -d maxwellwolf/mysql:1.0

docker run --name www --network=SRE -p 80:80 -d maxwellwolf/apache:1.0
