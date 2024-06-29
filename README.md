# Shortlinks Website Project

## Making Database
```
CREATE DATABASE shortlink_db;
```

```
USE shortlink_db;
```

```
CREATE TABLE shortlinks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    short_code VARCHAR(255) NOT NULL,
    original_url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

```

## Configuration
> Def Conf
```
cat > /etc/apache2/sites-available/000-default.conf
```
```
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Alias /shortlink /var/www/html/shortlink
    <Directory /var/www/html/shortlink>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

```

> .htaccess
```
cat > /var/www/html/shortlink/.htaccess
```
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?c=$1 [L,QSA]
```
```
sudo systemctl restart apache2
```

> Access
```
sudo chown -R www-data:www-data /var/www/html/shortlink
sudo chmod -R 755 /var/www/html/shortlink
```

>Struktur
/var/www/html/
├── index.html
└── shortlink
    ├── .htaccess
    └── index.php


