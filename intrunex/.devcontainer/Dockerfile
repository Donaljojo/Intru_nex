# Base image with PHP 8.2 and Composer preinstalled
FROM mcr.microsoft.com/devcontainers/php:8.2

# Install pgsql driver
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
 && mv ~/.symfony*/bin/symfony /usr/local/bin/symfony

# Install system dependencies and scanners
RUN apt-get update && apt-get install -y \
    unzip \
    nmap \
    nikto \
    wapiti \
    arachni \
    whatweb \
    && docker-php-ext-install xml \
    && rm -rf /var/lib/apt/lists/*

# Install Nuclei (latest)
RUN curl -s https://api.github.com/repos/projectdiscovery/nuclei/releases/latest \
| grep "browser_download_url.*linux_amd64.zip" \
| cut -d '"' -f 4 | wget -i - && \
unzip nuclei*.zip && \
mv nuclei /usr/local/bin/ && \
rm nuclei*.zip
