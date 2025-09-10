#!/bin/bash
while true; do
  php bin/console messenger:consume async --memory-limit=128M --time-limit=3600 -vvv
  echo "Worker crashed with exit code $?. Respawning in 5 seconds..."
  sleep 5
done
