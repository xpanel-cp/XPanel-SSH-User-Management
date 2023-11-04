#!/bin/bash
def_port=$(grep "PORT_PANEL=" /var/www/html/app/.env | awk -F "=" '{print $2}')
read -rp "Please enter the pointed domain / sub-domain name: " domain
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d $domain

sudo tee /etc/nginx/sites-available/default <<'EOF'
server {
    listen 80;
    server_name example.com;
    root /var/www/html/example;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
    location ~ /\.ht {
        deny all;
    }
     location /ws
    {
    proxy_pass http://127.0.0.1:8880/;
    proxy_redirect off;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_read_timeout 52w;
    }
}
server {
    listen 443 ssl;
    server_name example.com;

    root /var/www/html/example;
    index index.php index.html;

    ssl_certificate /etc/letsencrypt/live/domin/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domin/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    location /ws {
        if ($http_upgrade != "websocket") {
                return 404;
        }
        proxy_pass http://127.0.0.1:8880;
        proxy_redirect off;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_read_timeout 52w;
    }
}
server {
    listen serverPort ssl;
    server_name example.com;
    root /var/www/html/cp;
    index index.php index.html;

    ssl_certificate /etc/letsencrypt/live/domin/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domin/privkey.pem;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
    location ~ /\.ht {
        deny all;
    }
}
EOF
sed -i "s/serverPort/$def_port/g" /etc/nginx/sites-available/default
sed -i "s/domin/$domain/g" /etc/nginx/sites-available/default
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

sudo systemctl start nginx
sudo systemctl enable nginx
sudo systemctl reload nginx

multiin=$(echo "https://${domain}:$def_port/fixer/multiuser")
cat > /var/www/html/kill.sh << ENDOFFILE
#!/bin/bash
#By Alireza
i=0
while [ 1i -lt 10 ]; do 
cmd=(bbh '$multiin')
echo cmd &
  sleep 6
  i=(( i + 1 ))
done
ENDOFFILE
wait
sudo sed -i 's/(bbh/$(curl -v -H "A: B"/' /var/www/html/kill.sh
wait
sudo sed -i 's/cmd/$cmd/' /var/www/html/kill.sh
wait
sudo sed -i 's/1i/$i/' /var/www/html/kill.sh
wait
sudo sed -i 's/((/$((/' /var/www/html/kill.sh
chmod +x /var/www/html/kill.sh
wait
othercron=$(echo "https://${domain}:$def_port/fixer/other")
  cat >/var/www/html/other.sh <<ENDOFFILE
#!/bin/bash
#By Alireza
i=0
while [ 1i -lt 3 ]; do
cmd=(bbh '$othercron')
echo cmd &
sleep 17
i=(( i + 1 ))
done
ENDOFFILE
  wait
  sudo sed -i 's/(bbh/$(curl -v -H "A: B"/' /var/www/html/other.sh
  wait
  sudo sed -i 's/cmd/$cmd/' /var/www/html/other.sh
  wait
  sudo sed -i 's/1i/$i/' /var/www/html/other.sh
  wait
  sudo sed -i 's/((/$((/' /var/www/html/other.sh
  wait
  chmod +x /var/www/html/other.sh
(crontab -l | grep . ; echo -e "* * * * * /var/www/html/kill.sh") | crontab -
(crontab -l | grep . ; echo -e "* * * * * /var/www/html/other.sh") | crontab -
(crontab -l | grep . ; echo -e "0 */1 * * * /var/www/html/killlog.sh") | crontab -
(crontab -l ; echo "* * * * * wget -q -O /dev/null 'https://${domain}:$def_port/fixer/exp' > /dev/null 2>&1") | crontab -
clear
printf "\nHTTPS Address : https://${domain}:$def_port/login \n"
