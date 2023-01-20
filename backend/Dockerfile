FROM php:8.1.0-cli

RUN apt-get update && apt-get install vim -y && \
    apt-get install openssl -y && \
    apt-get install libssl-dev -y && \
    apt-get install wget -y && \
    apt-get install git -y && \
    apt-get install procps -y && \
    apt-get install htop -y && \
    apt-get install libcurl4-openssl-dev -y

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip


RUN cd /tmp && git clone https://github.com/openswoole/swoole-src.git && \
    cd swoole-src && \
    git checkout v22.0.0 && \
    phpize  && \
    ./configure --enable-openssl --enable-hook-curl --enable-http2 --enable-mysqlnd && \
    make && make install

RUN touch /usr/local/etc/php/conf.d/openswoole.ini && \
    echo 'extension=openswoole.so' > /usr/local/etc/php/conf.d/zzz_openswoole.ini

RUN wget -O /usr/local/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.2/dumb-init_1.2.2_amd64
RUN chmod +x /usr/local/bin/dumb-init

RUN apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . /app

COPY *.json /app
COPY vendor /app/


#RUN composer install


ENTRYPOINT ["/usr/local/bin/dumb-init", "--", "php"]
