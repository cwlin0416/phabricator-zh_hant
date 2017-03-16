<?php
$jsonFile = "/usr/local/lib/php/phabricator/src/.cache/i18n_strings.json";
if( isset($argv[1]) && file_exists($argv[1]) ) {
    $jsonFile = $argv[1];
}

echo "<?php\n";
?>
final class PhabricatorTradChineseTranslation
  extends PhutilTranslation {

  public function getLocaleCode() {
    return 'zh_Hant';
  }
  protected function getTranslations() {
    return <?php
class PhutilTranslation {
  function dumpTranslations() {
    return $this->getTranslations();
  }
}

require_once "PhabricatorTradChineseTranslation.php";

$trans = new PhabricatorTradChineseTranslation();
$transStrings = $trans->dumpTranslations();
$transStrings = array_filter($transStrings);

// Load new stromgs from JSON file
$json = file_get_contents($jsonFile);
$strings = json_decode($json);
$results = array();

// Merge new translations and old translations
foreach($strings as $string => $uses) {
	$results[$string] = null;
	if( !empty($transStrings[$string]) ) {
		$results[$string] = $transStrings[$string];
		unset($transStrings[$string]);
	}
}
$results = array_merge($results, $transStrings);

// Export as PHP var
var_export($results);
?>
;
  }

}
