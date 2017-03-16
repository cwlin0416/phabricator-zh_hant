#!/bin/sh
TEMP_PATH=$PWD
cd /usr/local/lib/php/ && phabricator/bin/i18n extract
echo $TEMP_PATH
cd $TEMP_PATH
php -f parseI18nStrings.php /usr/local/lib/php/phabricator/src/.cache/i18n_strings.json > PhabricatorTradChineseTranslation.php.new
mv PhabricatorTradChineseTranslation.php PhabricatorTradChineseTranslation.php.old
mv PhabricatorTradChineseTranslation.php.new PhabricatorTradChineseTranslation.php

