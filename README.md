**Test task: VPN Admin Panel.**


1. download
2. then type command "composer update"
3. run mysql_db.sql
4. I use this configuration of nginx:
<pre>
server {
    listen 80;
    server_name yiitest.yu www.yiitest.yu;

    fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    include        fastcgi_params;
    error_log /var/log/nginx/vg.error;
    access_log /var/log/nginx/vg.access;

    root /home/yura/wwwserver/yiitest/basic/web/;
    charset utf-8;
    client_max_body_size 100m;
    
    	set $yii_bootstrap "index.php";
	  try_files $uri $uri/ /$yii_bootstrap?$args;

    location / {
     
     index  index.html $yii_bootstrap;
     try_files $uri $uri/ /$yii_bootstrap?$args;
     rewrite ^/index.php(.*)$ /index.php?r=$1 last;

      }


    location ~ \.php$ {
  	fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    break;
      }
    }
</pre>

It also in file "nginxtestconfig", just change line with root   like 
<pre>
  root /home/yura/wwwserver/yiitest/basic/web/;
</pre>
to your path;
also may be you need change server_name.
