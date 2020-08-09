FROM ubuntu:bionic

ENV PROJECT_NAME my-scheduler


RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/* \
    && localedef -i en_US -c -f UTF-8 -A /usr/share/locale/locale.alias en_US.UTF-8
ENV LANG en_US.utf8

RUN apt-get update \
    && apt-get install -y apache2

COPY php.ini /etc/php/7.3/apache2/php.ini
COPY ../docker/apache/my-scheduler-vhost.conf /etc/apache2/sites-available/my-scheduler.conf

EXPOSE 80 443

CMD ["a2ensite my-scheduler", "service apache2 restart"]
