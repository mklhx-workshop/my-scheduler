# Dev Workflow

```shell
git clone https://github.com/mklhx-workshop/my-scheduler.git

cd my-scheduler

cd docker

docker-compose up
```

## Compose

### Database (MariaDB)

...

### PHP (PHP-FPM)

Composer is included

```shell
docker-compose run php-fpm composer 
```

To create database

```
docker-compose run php-fpm php bin/console doctrine:database:create
```

To run fixtures

```shell
docker-compose run php-fpm php bin/console doctrine:fixtures:load
```

### Webserver (Nginx)

...