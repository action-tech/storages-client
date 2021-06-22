FROM php:7.4-cli-alpine3.13

RUN apk add --update wget bash

RUN wget http://nexus.action-media.ru/repository/github-raw/php/psalm/4.3.0/psalm.phar \
    && chmod +x psalm.phar \
    && mv psalm.phar /usr/local/bin/psalm \
    && wget http://nexus.action-media.ru/repository/github-raw/php/phpcs/3.5.4/phpcs.phar \
    && chmod +x phpcs.phar \
    && mv phpcs.phar /usr/local/bin/phpcs \
    && wget http://nexus.action-media.ru/repository/github-raw/php/phpcbf/3.5.4/phpcbf.phar \
    && chmod +x phpcbf.phar \
    && mv phpcbf.phar /usr/local/bin/phpcbf \
    && wget http://nexus.action-media.ru/repository/github-raw/php/coverageChecker/0.11.2/diffFilter.phar \
    && chmod +x diffFilter.phar \
    && mv diffFilter.phar /usr/local/bin/diffFilter
