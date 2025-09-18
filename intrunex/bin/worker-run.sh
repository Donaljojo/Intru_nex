#!/bin/bash
while true; do
  php bin/console messenger:consume async --memory-limit=128M --time-limit=3600 -vvv
  echo "Worker crashed with exit code $?. Respawning in 5 seconds..."
  sleep 5
done
export PATH="$HOME/.symfony5/bin:$PATH"


echo 'export PATH="$HOME/.symfony5/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
sudo mv /home/codespace/.symfony5/bin/symfony /usr/local/bin/symfony
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
     mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

echo 'export PATH=/usr/bin:$PATH' >> ~/.bashrc
source ~/.bashrc
php -v
