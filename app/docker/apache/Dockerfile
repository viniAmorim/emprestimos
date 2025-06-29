# Use uma imagem php-apache que já vem com Apache e muitos módulos pré-instalados/habilitados.
# Escolha a versão do PHP que você usa (ex: 8.2, 8.3, 7.4).
FROM php:8.2-apache

# Define o fuso horário
ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Variáveis de ambiente
ENV APP_ROOT=/var/www/app
ENV SERVER_NAME=app.ucredcredito.com
ENV DOCUMENT_ROOT=${APP_ROOT}/public_html
ENV APACHE_LOG_DIR=/var/log/apache2 # Recomendo logs fora do /var/www para evitar problemas de permissão
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_RUN_USER=www-data

# Instala pacotes adicionais necessários para o ambiente Debian (a maioria dos php-apache são baseados em Debian)
# Remova os "apache2-mod-*" daqui, pois eles vêm com a imagem base
RUN apt-get update && apt-get install -y \
    tzdata \
    openssl \
    apache2-utils \
    # Outros pacotes que você possa precisar, mas não os módulos Apache
    && rm -rf /var/lib/apt/lists/*

# Cria diretório de logs e define permissões
RUN mkdir -p ${APACHE_LOG_DIR} && chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} ${APACHE_LOG_DIR}

WORKDIR ${APP_ROOT}

# Cria o diretório public_html e define permissões
RUN mkdir -p ${DOCUMENT_ROOT} \
    && chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} ${DOCUMENT_ROOT}

# Habilita módulos Apache que podem não estar habilitados por padrão,
# mas que são essenciais para sua configuração (SSL, Rewrite, Proxy, FCGI, Headers).
# Estes comandos `a2enmod` funcionam em imagens baseadas em Debian.
RUN a2enmod ssl \
    && a2enmod rewrite \
    && a2enmod proxy \
    && a2enmod proxy_fcgi \
    && a2enmod headers

# Remova esta linha se ela ainda estiver lá, pois ela cria um link simbólico incorreto.
# RUN ln -s ${APP_ROOT}/public_html/index.php ${DOCUMENT_ROOT}

# O httpd.conf padrão dessas imagens geralmente já inclui sites-enabled e carrega módulos.
# Você não precisará de um httpd.conf customizado tão extenso.
# Para evitar conflitos, vamos usar a configuração padrão do Apache para esta imagem
# e focar no seu VirtualHost.

# Copia seu VirtualHost customizado
COPY app.ucredcredito.com.conf /etc/apache2/sites-available/app.ucredcredito.com.conf

# Habilita o VirtualHost e desabilita o VirtualHost padrão (000-default.conf)
# O 000-default.conf pode estar na porta 80 ou 443 e causar conflitos.
RUN a2ensite app.ucredcredito.com.conf \
    && a2dissite 000-default.conf

EXPOSE 80 443

# O ENTRYPOINT padrão de php:X.Y-apache já inicia o Apache no foreground.