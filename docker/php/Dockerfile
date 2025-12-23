FROM php:8.3-fpm

# PHP-FPM用のポート
EXPOSE 9000
# Node.js用のポート（Viteなど用）
EXPOSE 5173

# 作成した設定ファイルをコンテナ内にコピー
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# 基本パッケージのインストール
RUN apt-get update \
    && apt-get -y install git zip unzip vim wget xz-utils libzip-dev procps \
    && docker-php-ext-install pdo_mysql mysqli zip

# Node.jsのインストール
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Composerのインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php \
    && ln -s /usr/bin/composer.phar /usr/bin/composer

# Node.jsのバージョン管理
ENV NODE_VERSION=20.9.0
RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION} \
    && . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION} \
    && . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

RUN node --version
RUN npm --version

# コンテナ内作業ディレクトリ
WORKDIR /var/www/html

# PHP-FPMをコンテナ起動時に自動起動
CMD ["php-fpm"]
