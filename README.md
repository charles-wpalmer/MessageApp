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
./run
```

## Discussion

### General Notes
1. Used https://github.com/carlosas/docker-for-symfony as a basis for the whole
Docker setup, instead of building it from scratch, seemed a reasonable setup,
had to trim down some Dockerfiles to make it more suitable, and cut out ELK 
stack for now.

2. Installed the Symfony CLI and created a fresh application that way.

3. Setting up the Entity, Migration, FormType and Repository for the Message
table. Bringing the logic out of the controller into the FormType, which deals
directly with the Message Entity.

4. Decided to add authentication to the App. Symfony, like Laravel, makes this 
really easy with set up commands that I used. Leaving me with essentially
just having to add the user_id foreign key onto the Message table.

5. The app used supervisord in order to keep the Consumer for the RabbitMQ
running at all times.

### Twilio

1. Used the Twilio SDK, and set 

Messaging:
https://www.nielsvandermolen.com/tutorial-symfony-4-messenger/

## Missing/Nice to have

1. Unit Tests: would have liked to added unit tests, however with time
constraints didnt prioritise, and decided to learn more about different aspects
of Symfony.

2. SymfonyCloud: discovered SymfonyCloud after integrating RabbitMQ the way I 
did, and it looks like it could have been useful. Instead of using docker
for other services such as RabbitMQ/Redis, this could have been used.

## Problems encountered

1. Had problems with Twilio API - couldn't get it to send to a verified number
on trial account.

2. Was going to try and use ngrok to supply a Valid WebHook url of the exposed
container. However, due to not being able to properly send Twilio Messages, this
was pointless. I also had problem the fact that ngrok assigns a random 
subdomain, which is very tricky to get into the config of the PHP container
to be used as a config variable to generate the callback URL.