# FROM ubuntu:bionic

# # fromt the doc
# # Given that it is a minimal install of Ubuntu, this image only includes the C, C.UTF-8, and POSIX locales by default. For most uses requiring a UTF-8 locale
# # https://hub.docker.com/_/ubuntu
# RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/* \
#     && localedef -i en_US -c -f UTF-8 -A /usr/share/locale/locale.alias en_US.UTF-8
# ENV LANG en_US.utf8


# # ENV HTTPD_PREFIX /etc/apache2
# # ENV PATH $HTTPD_PREFIX/bin:$PATH
# # RUN mkdir -p "$HTTPD_PREFIX" \
# #     && chown www-data:www-data "$HTTPD_PREFIX"
# # WORKDIR $HTTPD_PREFIX

# # RUN apt-get update && apt-get install -y --no-install-recommends \
# #     locales apache2 curl && \
# #     rm -rf /var/lib/apt/lists/* && \
# #     localedef -i fr_FR -c -f UTF-8 -A /usr/share/locale/locale.alias fr_FR.UTF-8

# # ENV LANG fr_FR.utf8

# # RUN a2enmod proxy_fcgi ssl rewrite proxy proxy_balancer proxy_http proxy_ajp
# # RUN sed -i '/Global configuration/a \
# # ServerName localhost \
# # ' /etc/apache2/apache2.conf
# # EXPOSE 80 443
# # RUN rm -f /run/apache2/apache2.pid
# # CMD apachectl  -DFOREGROUND -e info

FROM debian:stretch

ENV HTTPD_PREFIX /usr/local/apache2
ENV PATH $HTTPD_PREFIX/bin:$PATH
RUN mkdir -p "$HTTPD_PREFIX" \
    && chown www-data:www-data "$HTTPD_PREFIX"
WORKDIR $HTTPD_PREFIX

RUN apt-get update && apt-get install -y --no-install-recommends \
    locales apache2 curl && \
    rm -rf /var/lib/apt/lists/* && \
    localedef -i fr_FR -c -f UTF-8 -A /usr/share/locale/locale.alias fr_FR.UTF-8

ENV LANG fr_FR.utf8

RUN a2enmod proxy_fcgi ssl rewrite proxy proxy_balancer proxy_http proxy_ajp
RUN sed -i '/Global configuration/a \
ServerName localhost \
' /etc/apache2/apache2.conf
EXPOSE 80 443
RUN rm -f /run/apache2/apache2.pid
CMD apachectl  -DFOREGROUND -e info
