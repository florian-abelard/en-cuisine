## Installation sur le serveur VPS


### PHP

```bash
apt install php8.2 php8.2-fpm php8.2-pgsql php8.2-zip php8.2-xml php8.2-intl
chown florian:florian /var/run/php/php8.2-fpm.sock
```

### Serveur web

*Modification de la configuration générale*

Fichier `/etc/nginx/nginx.conf`
```conf
user florian;
http {
    fastcgi_buffers 16 16k; 
    fastcgi_buffer_size 32k;
}
```

*Virtual host web*

fichier `/etc/nginx/sites-available/encuisine.lesmoflo.fr.conf`

```json
server {
    listen 443 http2 ssl;

    # Redirect non-https traffic to https
    if ($scheme != "https") {
        return 301 https://$host$request_uri;
    }

    server_name encuisine.lesmoflo.fr;
    root /var/www/encuisine.lesmoflo.fr/web;

    gzip on;
    gzip_types text/html text/css application/json application/javascript;
    gzip_comp_level 6;
    gzip_min_length 64;
    gzip_proxied no-cache no-store private expired auth;

    location ~ \.(css|scss|js|svg|jpg|png|ttf|woff|eot|woff2)$ {
        expires 31536000s;
        add_header Pragma "public";
        add_header Cache-Control "max-age=31536000, public";
        try_files $uri $uri/ /index.html;
    }

    location / {
        add_header Pragma "no-cache";
        add_header Cache-Control "no-store";
        try_files $uri $uri/ /index.html;
    }

    error_log /var/log/nginx/encuisine.lesmoflo.fr_error.log;
    access_log /var/log/nginx/encuisine.lesmoflo.fr_access.log;

    ssl_certificate /etc/letsencrypt/live/encuisine.lesmoflo.fr/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/encuisine.lesmoflo.fr/privkey.pem; # managed by Certbot

    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
}
```

*Virtual host api*

fichier `/etc/nginx/sites-available/api.encuisine.lesmoflo.fr.conf`

```json
server {

    listen 443 http2 ssl;
 
    server_name api.encuisine.lesmoflo.fr;
    root /var/www/encuisine.lesmoflo.fr/backend/public;

    add_header "Access-Control-Allow-Origin" "*" always;
    add_header "Access-Control-Allow-Methods" "*" always;
    add_header "Access-Control-Allow-Headers" "Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, X-CSRF-Token, Authorization" always;
    add_header "Access-Control-Expose-Headers" "*" always;
    add_header "Access-Control-Max-Age" 3600 always;

    if ($request_method = 'OPTIONS') {
      return 204;
    }
 
    location / {
        # try to serve file directly, fallback to index.php
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;

        # see https://github.com/zendtech/ZendOptimizerPlus/issues/126
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    # return 404 for all other php files not matching the front controller
    # this prevents access to other php files you don't want to be accessible.
    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/api.encuisine.lesmoflo.fr_error.log;
    access_log /var/log/nginx/api.encuisine.lesmoflo.fr_access.log;

    ssl_certificate /etc/letsencrypt/live/api.encuisine.lesmoflo.fr/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/api.encuisine.lesmoflo.fr/privkey.pem; # managed by Certbot

    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
}
```

*Génération des certificats SSL*

Génération avec certbot, plugin nginx, challenge HTTP.

https://eff-certbot.readthedocs.io/en/stable/using.html#nginx
https://letsencrypt.org/fr/docs/challenge-types/

`certbot --nginx`

### Base de données

*Initialisation de la base*

```bash
su - postgres

createuser flo
createdb encuisine -O flo

psql
ALTER USER flo WITH PASSWORD '*****';
\q

```

*Paramètrage*

Gestion des droits dans le fichier `/etc/postgresql/11/main/pg_hba.conf`.
