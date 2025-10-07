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

echo '' >> ~/.bashrcexport PATH=/usr/bin:$PATH
source ~/.bashrc
php -v


sudo apt-get update
sudo apt-get install -y nmap
nmap -v



# Go to home directory
cd ~

# Remove any old partial clones
rm -rf nikto

# Clone the official repository
git clone https://github.com/sullo/nikto.git

# Move into the program folder
cd nikto/program

# Make the main Perl file executable
chmod +x nikto.pl


./nikto.pl -Version


sudo ln -sf $(pwd)/nikto.pl /usr/local/bin/nikto


export PATH="$HOME/nikto/program:$PATH"
source ~/.bashrc

