# Dev Workflow

```shell
git clone https://github.com/mklhx-workshop/my-scheduler.git

cd my-scheduler

cd docker

docker-compose up -d
```

## Database

### Create Database
```shell
cd docker
docker-compose run php-fpm php bin/console d:d:c
```

### Update Database Schema
```shell
cd docker
docker-compose run php-fpm php bin/console d:s:u -f
```

### Load Fixtures
```shell
docker-compose run php-fpm php bin/console doctrine:fixtures:load
```

## Composer
Composer is included in php-fpm image

```shell
docker-compose run php-fpm composer install
```

## Webpack
```shell
docker-compose run webpack yarn install
```