# From
FROM php:8.3.4-fpm-alpine3.19

# Labels
LABEL creatorName="Vagner dos Santos Cardoso"
LABEL creatorEmail="vagnercardosoweb@gmail.com"

# Set timezone
ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install non-dev dependencies
RUN set -eux && \
  apk add --no-cache \
  git vim zip unzip bash curl tzdata icu-libs \
  c-client make ca-certificates imap gmp gettext libssh2 yaml \
  libintl libxslt libpng libwebp libjpeg-turbo freetype imap \
  linux-headers oniguruma libxslt libpq vips \
  gmp libzip libxml2 freetds \
  sudo && \
  rm -rf /var/cache/apk/*

# Install dependencies and PHP extensions
RUN set -eux && \
  apk add --no-cache --virtual .build_deps \
  libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev imap-dev \
  linux-headers oniguruma-dev libxslt-dev postgresql-dev vips-dev \
  libssh2-dev gmp-dev libzip-dev libxml2-dev freetds-dev yaml-dev \
  $PHPIZE_DEPS && \
  docker-php-ext-install \
  mysqli \
  pdo_mysql \
  pdo_pgsql \
  pgsql \
  bcmath \
  mbstring \
  xml \
  gd \
  exif \
  zip \
  soap \
  intl \
  xsl \
  pcntl \
  sockets \
  sysvmsg \
  sysvsem \
  sysvshm \
  opcache \
  imap \
  gmp && \
  pecl install -o -f xdebug && \
  docker-php-ext-enable xdebug && \
  pecl install -o -f yaml && \
  docker-php-ext-enable yaml && \
  pecl install -o -f redis && \
  docker-php-ext-enable redis && \
  pecl install -o -f mongodb && \
  docker-php-ext-enable mongodb && \
  pecl install -o -f apcu && \
  docker-php-ext-enable apcu && \
  pecl install -o -f ssh2 && \
  docker-php-ext-enable ssh2 && \
  rm -rf /tmp/pear && \
  docker-php-source delete && \
  apk del --purge .build_deps && \
  rm -rf /var/cache/apk/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy php settings
COPY ./php.ini ${PHP_INI_DIR}/conf.d/99-php.ini

# Copy entrypoint script
COPY ./entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Make entrypoint script executable
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Workdir
ENV WORKDIR=/var/www/app
RUN mkdir -p ${WORKDIR}
WORKDIR ${WORKDIR}

# Expose port
EXPOSE 9000

# Define o script como o ENTRYPOINT do container
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
# O CMD padrão da imagem php-fpm (que será passado para "$@" no entrypoint.sh)
CMD ["php-fpm"]
