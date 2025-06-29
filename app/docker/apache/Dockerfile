FROM httpd:2.4-alpine

# Instala ferramentas e módulos necessários para Alpine, incluindo SSL
RUN apk add --no-cache \
    tzdata \
    openssl \
    apache2-utils \
    apache2-mod-ssl \
    apache2-mod-rewrite \
    apache2-mod-proxy \
    apache2-mod-proxy-fcgi \
    apache2-mod-headers # Necessário para cabeçalhos HTTP como HSTS

# Define o fuso horário
ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Variáveis de ambiente
ENV APP_ROOT=/var/www/app
ENV SERVER_NAME=app.ucredcredito.com # Alterado para seu domínio real
ENV DOCUMENT_ROOT=${APP_ROOT}/public_html
ENV APACHE_LOG_DIR=/var/log/apache2 # Alterado para um local padrão de logs, fora de /var/www
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_RUN_USER=www-data

# Cria diretório de logs e define permissões
RUN mkdir -p ${APACHE_LOG_DIR} && chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} ${APACHE_LOG_DIR}

WORKDIR ${APP_ROOT}

# Cria o diretório public_html e define permissões
RUN mkdir -p ${DOCUMENT_ROOT} \
    && chown -R ${APACHE_RUN_USER}:${APACHE_RUN_GROUP} ${DOCUMENT_ROOT}

# Garante que os logs do Apache possam ser acessados pelo Certbot no host (se necessário)
# Isso é para o caso de você usar o webroot method do Certbot diretamente no host.
# Se o volume /etc/letsencrypt já resolve, não precisa de mais aqui.
# RUN mkdir -p /etc/letsencrypt/live/app.ucredcredito.com/
# RUN chmod 755 /etc/letsencrypt/live/app.ucredcredito.com/

# Este link simbólico não é recomendado para DocumentRoot; o DocumentRoot deve ser o diretório base da aplicação.
# A página index.php deve estar dentro de public_html.
# REMOVA OU COMENTE esta linha: RUN ln -s ${APP_ROOT}/public_html/index.php ${DOCUMENT_ROOT}

# Copia o arquivo httpd.conf customizado
COPY ./httpd.conf /usr/local/apache2/conf/httpd.conf

# Copia e habilita o VirtualHost HTTP e HTTPS
# Vamos nomear o arquivo de configuração de forma mais específica e copiá-lo para sites-available
COPY app.ucredcredito.com.conf /etc/apache2/sites-available/app.ucredcredito.com.conf
# Habilita o site (cria link simbólico)
RUN ln -s /etc/apache2/sites-available/app.ucredcredito.com.conf /etc/apache2/sites-enabled/app.ucredcredito.com.conf

EXPOSE 80 443

# O ENTRYPOINT padrão do httpd:2.4-alpine já inicia o Apache no foreground