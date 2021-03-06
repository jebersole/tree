FROM ubuntu:18.04
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get clean && apt-get -y update && apt-get install -y tzdata locales curl software-properties-common git \
  && locale-gen en_US.UTF-8 
RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php7.0-fpm php7.0-mysql php7.0-zip php7.0-mbstring php7.0-soap php7.0-curl php7.0-xml php7.0-gd php7.0-mcrypt php-xdebug

RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php/7.0/cli/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php/7.0/fpm/php.ini
RUN sed -i "s/display_errors = Off/display_errors = On/" /etc/php/7.0/fpm/php.ini
RUN sed -i "s/upload_max_filesize = .*/upload_max_filesize = 10M/" /etc/php/7.0/fpm/php.ini
RUN sed -i "s/post_max_size = .*/post_max_size = 12M/" /etc/php/7.0/fpm/php.ini
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/7.0/fpm/php.ini

RUN sed -i -e "s/pid =.*/pid = \/var\/run\/php7.0-fpm.pid/" /etc/php/7.0/fpm/php-fpm.conf
RUN sed -i -e "s/error_log =.*/error_log = \/proc\/self\/fd\/2/" /etc/php/7.0/fpm/php-fpm.conf
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/7.0/fpm/php-fpm.conf
RUN sed -i "s/listen = .*/listen = 7000/" /etc/php/7.0/fpm/pool.d/www.conf
RUN sed -i "s/;catch_workers_output = .*/catch_workers_output = yes/" /etc/php/7.0/fpm/pool.d/www.conf

RUN curl https://getcomposer.org/installer > composer-setup.php && php composer-setup.php && mv composer.phar /usr/local/bin/composer && rm composer-setup.php

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 7000
CMD ["php-fpm7.0"]
