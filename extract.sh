#!/bin/sh
I18N_STRING_FILE=/usr/local/lib/php/phabricator/src/.cache/i18n_strings.json
TEMP_PATH=$PWD
cd /usr/local/lib/php/ && phabricator/bin/i18n extract
cd $TEMP_PATH
php -f parseI18nStrings.php $I18N_STRING_FILE > PhabricatorTradChineseTranslation.php.new
mv PhabricatorTradChineseTranslation.php PhabricatorTradChineseTranslation.php.old
mv PhabricatorTradChineseTranslation.php.new PhabricatorTradChineseTranslation.php

