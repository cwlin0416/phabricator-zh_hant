# Phabricator 繁體中文語系

Phabricator 官方網站 http://phabricator.org/

![Screenshot](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/screenshot.png)
![Differential](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/Differential.png)
![Diffusion](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/Diffusion.png)
![Diffusion2](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/Diffusion2.png)
![Maniphest](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/Maniphest.png)
![Project](https://raw.githubusercontent.com/cwlin0416/phabricator-zh_hant/master/Project.png)

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
 * 升級資料庫 (`phabricator/bin/storage upgrade`)
 * 重啟 Daemon (`phabricator/bin/phd start`)
 * 重啟網頁伺服器 (`service apache24 start`)

### 範例升級 Shell Script

```
#!/bin/sh

set -e
set -x

# This is an example script for updating Phabricator, similar to the one used to
# update <https://secure.phabricator.com/>. It might not work perfectly on your
# system, but hopefully it should be easy to adapt. This script is not intended
# to work without modifications.

# NOTE: This script assumes you are running it from a directory which contains
# arcanist/, libphutil/, and phabricator/.

ROOT='/usr/local/lib/php' # 請修改此行為 Phabricator 所在路徑, 此處以 FreeBSD 的路徑為例

### UPDATE WORKING COPIES ######################################################

cd $ROOT/libphutil
git pull

cd $ROOT/arcanist
git pull

cd $ROOT/phabricator
git pull


### CYCLE WEB SERVER AND DAEMONS ###############################################

# Stop daemons.
service phd stop

# If running the notification server, stop it.
# $ROOT/phabricator/bin/aphlict stop

# Stop the webserver (apache, nginx, lighttpd, etc). This command will differ
# depending on which system and webserver you are running: replace it with an
# appropriate command for your system.
# NOTE: If you're running php-fpm, you should stop it here too.

service apache24 stop


# Upgrade the database schema. You may want to add the "--force" flag to allow
# this script to run noninteractively.
$ROOT/phabricator/bin/storage upgrade --force

# Restart the webserver. As above, this depends on your system and webserver.
# NOTE: If you're running php-fpm, restart it here too.
service apache24 start

# Restart daemons.
service phd start

# If running the notification server, start it.
# $ROOT/phabricator/bin/aphlict start
```

## 製作 Phabricator 語系

### 產生語系資源

#### 新版
新版的 Phabricator 不會直接輸出 PHP 格式，且所產生的語系資源改使用 json 格式儲存於 Phabricator 目錄下的 `/src/.cache/i18n_strings.json`
使用指令擷取目前程式中可翻譯的字串
`cd /usr/local/lib/php/ && phabricator/bin/i18n extract`
接著使本檔案庫所附的用將 `/src/.cache/i18n_strings.json` 的翻譯轉換與舊的翻譯合併為 PHP Class
`php -f parseI18nStrings.php /usr/local/lib/php/phabricator/src/.cache/i18n_strings.json > PhabricatorTradChineseTranslation.php.new`

所有動作執行 `extract.sh` 便可完成，執行前要先確認 i18n_strings.json 的檔案路徑是否正確

#### 舊版
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
Schema                   | 架構          | 資料庫架構
Poll                     | 調查          | 決策調查
Staging Area             | 臨時區        | 整合測試與編譯測試用的臨時區
Build, Build Plan        | 建置          | 建置計劃
File Storage             | 檔案儲存庫    |
Transforms               | 轉換          |
Package                  | 套件          | 擁有者(Owner)管理以套件 (Package) 為單位
Contributor              | 貢獻者        | 協議貢獻者
Signed                   | 簽署          | 簽署協議 
Flag, Flagged            | 旗幟, 旗標    | n. 旗幟, v. 旗標
Initiatives              | 提案          | Fund Initiatives
Blog                     | 網誌         | Phame Blog
Post                     | 文章          | Phame Post
Paste                    | 剪貼簿        |
Passphrase               | 暗號          |
Development, Production  | 開發/線上模式 | 
Automation Blueprint     | 自動化藍圖    |
Badge                    | 徽章          |
Revision Blocking, Block | 阻擋          | 阻擋, 無法繼續審查
Commandeer               | 徵用, 徵用修訂|
Resign                   | 放棄          | 放棄審查
Parent Task/Subtask      | 父工作/子工作 | 工作
Publisher                | 發行人        | 套件發行人
Favorite                 | 我的最愛      | 自訂選單項目
Assignee                 | 受託人        | 工作的受託人
