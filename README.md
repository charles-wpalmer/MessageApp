# Message App

This application will take a max 140 character string, and send it to a given
phone number. Built Using:


1. Symfony
2. RabbitMQ
3. Redis
4. MySQL/Doctrine

## Usage

Use Docker to set up this project's local development environment, contains 5 
containers within the network:

 - container_redis: used as the Redis serer
 - container_php: container for the actual application
 - container_nginx: nginx web server, will forward request to container_php
 - container_mysql: mysql database server
 - container_rabbit: RabbitMQ queue server

```sh
docker-compose build
docker-compose up -d
```

## Discussion

1. Used https://github.com/carlosas/docker-for-symfony as a basis for the whole
Docker setup, instead of building it from scratch, seemed a reasonable setup,
had to trim down some Dockerfiles to make it more suitable, and cut out ELK 
stack for now.

3. Installed the Symfony CLI and created a fresh application that way.

2. Setting up the Entity, Migration, FormType and Repository for the Message
table. Bringing the logic out of the controller into the FormType, which deals
directly with the Message Entity.

Messaging:
https://www.nielsvandermolen.com/tutorial-symfony-4-messenger/
https://www.nielsvandermolen.com/tutorial-symfony-4-messenger/
