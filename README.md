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
> Permission
```
sudo a2enmod rewrite
sudo systemctl restart apache2
```

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
# Aktifkan mod_rewrite
RewriteEngine On

# Atur aturan untuk shortlink
# Arahkan permintaan ke redirect.php dengan kode shortlink sebagai parameter
RewriteRule ^shortlink/([a-zA-Z0-9]+)$ redirect.php?code=$1 [L,QSA]

# Atur aturan untuk semua permintaan lain menuju index.php
# Permintaan tanpa parameter 'code' menuju index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]

```
```
sudo systemctl restart apache2
```

> Access
```
sudo chown www-data:www-data /var/www/html/shortlink/.htaccess
sudo chown www-data:www-data /var/www/html/shortlink/redirect.php
sudo chown www-data:www-data /var/www/html/shortlink/index.php
sudo chmod 644 /var/www/html/shortlink/.htaccess
sudo chmod 644 /var/www/html/shortlink/redirect.php
sudo chmod 644 /var/www/html/shortlink/index.php
```


