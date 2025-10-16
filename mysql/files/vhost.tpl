server {
    listen 443 ssl;
    server_name _;
    
    root /var/www/html;
    index index.php index.html index.htm;

    ssl_certificate /etc/ssl/certs/test.pem;
    ssl_certificate_key /etc/ssl/private/test.key;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}


server {

        listen 80;
        server_name _;
        return 301 https://$host$request_uri;
}
