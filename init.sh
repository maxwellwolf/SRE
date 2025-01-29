#!/bin/bash

docker network rm sre_default
docker network create sre_default

docker rmi php_apache
docker build -t php_apache ./apache/

docker rmi db_mysql
docker build -t db_mysql ./mysql/

docker rm db --force
docker run --name db --network=sre_default -e MYSQL_ROOT_PASSWORD=987654321 -e MYSQL_USER=admin -e MYSQL_PASSWORD=admin -e MYSQL_DATABASE=SRE -p 3306:3306 -d db_mysql --default-authentication-plugin=mysql_native_password

docker rm www --force
docker run --name www --mount type=bind,src=/home/ec2-user/SRE,dst=/var/www/html,ro --network=sre_default --link db:db -p 80:80 -d php_apache
