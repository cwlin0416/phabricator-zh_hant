# Phabricator 繁體中文語系

Phabricator 官方網站 http://phabricator.org/

![Screenshot](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/screenshot.png)

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


### 設定服務
https://secure.phabricator.com/book/phabricator/article/configuration_guide/
#### Apache
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

接下來便可進入 `phabricator.mycompany.com` 依提示完成剩下的安裝作業

##安裝 Phabricator 語系

###### 安裝語系檔
將 phabricator-zh_hant 資料夾放置於 `./phabricator/src/extensions/` 目錄底下，以 FreeBSD 為例:
```
cd /usr/local/lib/php/phabricator/src/extensions/
git clone https://github.com/cwlin0416/phabricator-zh_hant.git
```

###### 修正語系支援
目前 github 上的最新版本已無須再套用這個修補
切換語系後若出現錯誤訊息可以有兩種選擇: 1. 自行修改, 2. 更新到最新版

舊版 Phabricator 需額外修改

`/usr/local/lib/php/libphutil/src/internationalization/PhutilTranslator.php` 

加入繁體中文的語系方可正常使用

```
@@ -190,6 +190,7 @@ final class PhutilTranslator extends Phobject {
         return $plural;
 
       case 'ko_KR':
+      case 'zh_Hant':
         list($singular, $plural) = $translations;
         if ($variant == 1) {
           return $singular;
```

##升級 Phabricator
https://secure.phabricator.com/book/phabricator/article/upgrading/

升級 Phabricator 必須使用 Github 上的檔案庫, 因此 FreeBSD 採用 Ports 安裝的使用者需自行將安裝的 phabricator/, arcanist/, libphutil/ 三個目錄替換為 git 的版本。

 * 停止網頁伺服器 (`service apache24 stop`)
 * 停止 Daemon (`phabricator/bin/phd stop`)
 * 更新原始碼
   * `phabricator/ $ git checkout stable`
   * `phabricator/ $ git pull`
   * `arcanist/ $ git checkout stable`
   * `arcanist/ $ git pull`
   * `libphutil/ $ git checkout stable`
   * `libphutil/ $ git pull`
 * 升級資料庫 (`phabricator/bin/phd start`)
 * 重啟網頁伺服器 (`service apache24 start`)

##製作 Phabricator 語系

### 產生語系資源
請使用指令擷取目前程式碼中可翻譯的字串
`./phabricator/bin/i18n extract ./phabricator/src > extractStrings`
擷取完之後將該字串依語系擴充套件的 API 文件放到新語系類別的 getTranslations() 函數中

### 建立語系擴充套件
https://secure.phabricator.com/book/phabcontrib/article/internationalization/


## 術語表
詞                       | 翻譯          | 說明 
------------------------ | ------------- | -------------
Review                   | 審查          |
Audit                    | 稽查          |
Assign                   | 分配          |
Repository               | 檔案庫        |
Revision                 | 修訂          | 與版本管理的修訂不太一樣，是指 Differential 審查程式碼的申請項目
Needs Triage             | 需要分級      | 新增完工作的預設狀態, 代表讓工作需要先區分優先權等級以便後續動作
Conpherence              | 會議          | 會議，為 Conference 的協音
Room                     | 會議室        | 由 Conpherence 所使用，因此譯為會議室
Column                   | 欄            | 用在工作看板 (Workboard) 的欄
"Unbreak Now!"           | 緊急!         | 工作優先權狀態, 需要立即處理的工作
Dashboard                | 資訊看板      |
Panel                    | 面板          | 資訊看板的面板
Commit                   | 提交          | 版本管理系統的一個提出修訂的動作
Image Macro, Meme        | 貼圖          | Image Macro 指的是加上文字的貼圖，Meme 是在網路上流行/散佈的一個點子
Credential               | 憑證          | 密碼憑證
Policy                   | 原則          | 存取原則，用來規範存取的方式
Mock                     | 模型          | 畫面模型 
Patch                    | 修補          | 修補檔 
Provider, Auth Provider  | 提供者        | 認証提供者
Pin, Pinned              | 釘選          | 釘選應用程式
Filetree                 | 檔案樹        |
Tags                     | 標籤          | 專案標籤
Token                    | 獎勵, Token   | 頒發獎勵, API Token
Multi-Factor auth        | 多重認証      | 

