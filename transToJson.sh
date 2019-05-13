#!/usr/local/bin/php
<?php
class PhutilTranslation {
  function dumpTranslations() {
    return $this->getTranslations();
  }
}

require_once "PhabricatorTradChineseTranslation.php";

$trans = new PhabricatorTradChineseTranslation();
$transStrings = $trans->dumpTranslations();
$transStrings = array_filter($transStrings);
echo json_encode($transStrings);
