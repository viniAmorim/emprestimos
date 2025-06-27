FROM httpd:2.4-alpine

# Configuração de timezone
ENV TZ=America/Sao_Paulo
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo "${TZ}" > /etc/timezone && \
    apk del tzdata

# Instala dependências
RUN apk add --no-cache \
    apache2-ssl \
    apache2-utils \
    openssl \
    && rm -rf /var/cache/apk/*

# Configuração de variáveis
ENV APP_ROOT=/var/www/app
ENV SERVER_NAME=app.ucredcredito.com
ENV DOCUMENT_ROOT=${APP_ROOT}/public_html
ENV APACHE_LOG_DIR=${APP_ROOT}/docker/apache/logs

# Cria diretórios e configura permissões
WORKDIR ${APP_ROOT}
RUN mkdir -p ${DOCUMENT_ROOT} ${APACHE_LOG_DIR} && \
    chown -R www-data:www-data ${APP_ROOT}

# Habilita módulos SSL e Rewrite
RUN sed -i \
    -e '/LoadModule ssl_module modules\/mod_ssl.so/s/^#//g' \
    -e '/LoadModule rewrite_module modules\/mod_rewrite.so/s/^#//g' \
    -e '/LoadModule socache_shmcb_module modules\/mod_socache_shmcb.so/s/^#//g' \
    /usr/local/apache2/conf/httpd.conf

# Configuração do SSL
RUN mkdir -p /usr/local/apache2/conf/ssl && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /usr/local/apache2/conf/ssl/server.key \
    -out /usr/local/apache2/conf/ssl/server.crt \
    -subj "/C=BR/ST=Sao_Paulo/L=Sao_Paulo/O=UCred/CN=${SERVER_NAME}"

# Copia configurações
COPY ./httpd.conf /usr/local/apache2/conf/httpd.conf
COPY ./localhost.conf /usr/local/apache2/conf/extra/localhost.conf

# Portas expostas
EXPOSE 80
EXPOSE 443

# Comando de inicialização
CMD ["httpd-foreground"]