<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
<?php

$path = $_GET['path'];
$source = $_GET['source'];
$destination = $_GET['destination'];

$strings = array();

$source_files = glob($path . '/' . $source. "/*.php");
$destination_files = glob($path . '/' . $destination. "/*.php");

echo '<h3>Load ' . count($source_files) . ' language files from ' . $source . ' and ' . count($destination_files) . ' language files from ' . $destination . '.</h3>';

foreach ($source_files as $filename)
{
  echo '<hr>Source: '.$filename . ' | Destination: ';

  // Check if source file exists in destination and if not, skip to next file
  if(file_exists(str_replace($source, $destination, $filename))) {
    echo str_replace($source, $destination, $filename);
  }else{
    echo '<strong style="color: red;">File does not exist in destination!</strong>';
    continue;
  }

  // Load source language files
  include($filename);

  foreach($lang as $key => $value) {
    $strings[$source][$filename][$key] = $value;

    unset($lang[$key]);
  }

  // Load destination language files
  include(str_replace($source, $destination, $filename));

  foreach($lang as $key => $value) {
    $strings[$destination][str_replace($source, $destination, $filename)][$key] = $value;

    unset($lang[$key]);
  }

  echo '<br><br>-> Load ' . count($strings[$source][$filename]) . ' language strings from ' . $source . ' and ' . count($strings[$destination][str_replace($source, $destination, $filename)]) . ' language strings from ' . $destination . '.';

  if(array_diff_key($strings[$source][$filename], $strings[$destination][str_replace($source, $destination, $filename)])) {
    echo '<br><br>-> <strong style="color: red;">Missing the following strings from ' . $source . ' in ' . $destination . '.</strong>';

    foreach(array_diff_key($strings[$source][$filename], $strings[$destination][str_replace($source, $destination, $filename)]) as $key => $value) {
      echo "<br><br>\$lang['" . $key . "'] = " . '"";';
      echo '<br>// ' . $source . ': ' . $strings[$source][$filename][$key];
    }
  }
}

?>
</body>
</html>
