#!/bin/bash

# Zapisuje początek procesu
echo "Deployment started at $(date)"

# Kopiowanie pliku konfiguracyjnego Nginx
if cp nginx.default /etc/nginx/sites-available/default; then
    echo "Nginx configuration file copied successfully."
else
    echo "Failed to copy Nginx configuration file."
    exit 1
fi

# Restart Nginx, aby zastosować nową konfigurację
if service nginx restart; then
    echo "Nginx restarted successfully."
else
    echo "Failed to restart Nginx."
    exit 1
fi

echo "Deployment finished at $(date)"
