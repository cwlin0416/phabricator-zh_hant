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

$json = file_get_contents($jsonFile);
$strings = (array)json_decode($json);

function getBelongApp($uses) {
	$appNames = array();
	foreach($uses as $use) {
		$ex = explode("/", $use->file);
		if( $ex[1] == 'applications' ) {
			$app = $ex[2];
		} else {
			$app = $ex[1];
		}
		$appNames[] = $app;
	}
	// Count occuries and get most
	$appStat = array_count_values($appNames); 
	arsort($appStat);
	$mostApp = array_keys($appStat)[0];
	
	if( count($appStat) > 2) {
		$mostApp = 'common';
	}
	return $mostApp;
}
$categoryStrings = array();
$categoryStrings['common'] = array();
foreach($strings as $string => $uses) {
	$category = null;
	$category = getBelongApp($uses->uses);
	$categoryStrings[$category][$string] = null;
	if( !empty($transStrings[$string])) {
		$categoryStrings[$category][$string] = $transStrings[$string];
		unset($transStrings[$string]);
	}
}
$categoryStrings['unused'] = $transStrings;
echo "array (\n";
foreach($categoryStrings as $category => $strings) {
	$lineWidth = 75;
	$categoryName = ucfirst($category);
	echo "  // ". str_repeat("-", $lineWidth). "\n";
	echo "  // ". str_pad($categoryName, $lineWidth, " ", STR_PAD_BOTH). "\n";
	echo "  // ". str_repeat("-", $lineWidth). "\n";
	echo substr(var_export($strings, true), 7, -1). "\n";
}
echo ")";
?>
;
  }

}
