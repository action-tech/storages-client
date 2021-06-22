FROM nexus.action-media.ru/docker-action-base/base-service-php74:latest as local

ENV PHP_DISPLAY_ERRORS 0
ENV PHP_ERROR_REPORTING 22527

WORKDIR /var/www/

# setup
RUN yum makecache \
    && yum -y install git \
                      unzip \
                      php-pecl-xdebug \
    && yum -y autoremove \
    && yum clean all \
    && rm -rf /var/cache/yum/

# custom ini
COPY build/docker/app/configs /

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
