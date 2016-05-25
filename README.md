# Phabricator 繁體中文語系

Phabricator 官方網站 http://phabricator.org/

##安裝 Phabricator
https://secure.phabricator.com/book/phabricator/article/installation_guide/

### 安裝需求
* 作業系統
  * Linux
  * FreeBSD
  * Mac OS X
  * Solaris
* 網頁伺服器
  * Apache: 安裝使用 Apache + mod_php.
  * nginx: 安裝使用 nginx + php-fpm.
* MySQL: 建議 MySQL 5.5 或更新版本
* PHP: 需要 PHP 5.2 或更新版本
* 網域名稱 (如 `phabricator.mycompany.com`)

### FreeBSD ###
###### 使用 Ports 安裝
`cd /usr/ports/devel/phabricator/ && make install clean`

###### 安裝並啟動 Phabricator Daemon 服務
```
echo phd_enable="YES" >> /etc/rc.conf
service phd start
```

###### 安裝完的程式分別有 arcanist, libphutil, phabricator 位於
* `/usr/local/lib/php/arcanist/`
* `/usr/local/lib/php/libphutil/`
* `/usr/local/lib/php/phabricator/`

### Ubuntu ###
###### 使用 Shell Script 安裝
下載官方提供的 [install_ubuntu.sh](https://secure.phabricator.com/diffusion/P/browse/master/scripts/install/install_ubuntu.sh) 到欲安裝的目錄 (例: `/usr/share/php/``) 並執行
```
cd /usr/share/php/ && wget https://secure.phabricator.com/diffusion/P/browse/master/scripts/install/install_ubuntu.sh
chmod 755 install_ubuntu.sh
./install_ubuntu.sh
```

###### 啟動 Phabricator Daemon 服務
`/usr/share/php/phabricator/bin/phd start`

#### 安裝完的程式分別有 arcanist, libphutil, phabricator 位於
* `/usr/share/php/arcanist/`
* `/usr/share/php/libphutil/`
* `/usr/share/php/phabricator/`


### 設定 Apache 服務
###### 加入設定檔
以 FreeBSD 為例，可加入 `/usr/local/etc/apache24/Includes/phabricator.conf` 檔案如下:
```
<VirtualHost *>
  # Change this to the domain which points to your host.
  ServerName phabricator.mycompany.com

  # Change this to the path where you put 'phabricator' when you checked it
  # out from GitHub when following the Installation Guide.
  #
  # Make sure you include "/webroot" at the end!
  DocumentRoot /usr/local/lib/php/phabricator/webroot
  <Directory "/usr/local/lib/php/phabricator/webroot">
    Require all granted
  </Directory>

  RewriteEngine on
  RewriteRule ^/rsrc/(.*)     -                       [L,QSA]
  RewriteRule ^/favicon.ico   -                       [L,QSA]
  RewriteRule ^(.*)$          /index.php?__path__=$1  [B,L,QSA]
</VirtualHost>
```
###### 重啟 Apache
於 FreeBSD

`service apache24 restart`

於 Ubuntu 

`service apache2 restart`

##安裝 Phabricator 語系
將 phabricator-zh_hant 資料夾放置於 `./phabricator/src/extensions/` 目錄底下，以 FreeBSD 為例:
```
cd /usr/local/lib/php/phabricator/src/extensions/
git clone https://github.com/cwlin0416/phabricator-zh_hant.git
```

##製作 Phabricator 語系

### 產生語系資源
請使用以指令擷取目前程式碼中可翻譯的字串
`./phabricator/bin/i18n extract ./phabricator/src > extractStrings`
擷取完之後將該字串依語系擴充套件的 API 文件放到新語系類別的 getTranslations() 函數中

### 建立語系擴充套件
https://secure.phabricator.com/book/phabcontrib/article/internationalization/
