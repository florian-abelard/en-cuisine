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

*Virtual host*

fichier `/etc/nginx/sites-available/encuisine.lesmoflo.fr.conf`

```json
server {
 
    server_name encuisine.lesmoflo.fr;
    root /var/www/encuisine.lesmoflo.fr/application/public;

    listen 443 ssl;

    ssl_certificate /etc/letsencrypt/live/encuisine.lesmoflo.fr/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/encuisine.lesmoflo.fr/privkey.pem; # managed by Certbot

    include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot

    # Redirect non-https traffic to https
    if ($scheme != "https") {
        return 301 https://$host$request_uri;
    }
 
    location / {
        # try to serve file directly, fallback to index.php
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;

        # When you are using symlinks to link the document root to the
        # current version of your application, you should pass the real
        # application path instead of the path to the symlink to PHP
        # FPM.
        # Otherwise, PHP's OPcache may not properly detect changes to
        # your PHP files (see https://github.com/zendtech/ZendOptimizerPlus/issues/126
        # for more information).
        # Caveat: When PHP-FPM is hosted on a different machine from nginx
        #         $realpath_root may not resolve as you expect! In this case try using
        #         $document_root instead.
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        # Prevents URIs that include the front controller. This will 404:
        # http://domain.tld/index.php/some-path
        # Remove the internal directive to allow URIs like this
        internal;
    }

    # return 404 for all other php files not matching the front controller
    # this prevents access to other php files you don't want to be accessible.
    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/encuisine.lesmoflo.fr_error.log;
    access_log /var/log/nginx/encuisine.lesmoflo.fr_access.log;
}
```

*Génération des certificats SSL*


### Base de données
