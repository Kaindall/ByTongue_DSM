# Usa uma imagem base oficial do PHP 8.2
FROM php:8.2-cli

# Define o diretório de trabalho dentro do contêiner
WORKDIR /app

# Instala as dependências necessárias para as extensões
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && docker-php-ext-install \
    curl \
    intl \
    mysqli \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Copia os arquivos do projeto para o contêiner
COPY . /app

# Expõe a porta 8000 para permitir o acesso à aplicação
EXPOSE 8000

# Define o comando para iniciar a aplicação
CMD ["php", "-c", "php.ini", "-S", "0.0.0.0:8000", "src/App.php"]