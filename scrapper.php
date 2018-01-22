<?php 

  $start = 3089245;
  $file = fopen('data.json', 'w') or die('Error: Unable to create file in this directory.');
  fwrite($file, '[' . "\r\n");

  function scrape($id) {
    // get page contents
    $page = file_get_contents('http://www.utahsright.com/profile.php?id=' . $id);
    // replace excesive whitespace
    $page = preg_replace('/\s+/', ' ', $page);
    // extract data
    preg_match('~<h1 style="color:#333333;">(.*?)</h1>~', $page, $name);
    preg_match('~style="text-decoration:none; color:#0066CC;">(.*?)</a>~', $page, $location);
    // check for position in two places
    preg_match('~<h3 style="color:#006699;">(.*?)</h3>~', $page, $position);
    if (!$position[1]) {
      preg_match('~<h3 style="color:#666666;">(.*?)</h3>~', $page, $position);
    }
    // cache returned data
    $name = $name[1];
    $location = $location[1];
    $position = $position[1];
    // return results
    if ($name && $location && $position) {
      return [$name, $location, $position];
    } else {
      return false;
    }
  }

  function console($msg) {
    echo $msg . "\r\n";
  }

  function keyValue($key, $value) {
    return '"' . $key . '": "' . $value . '"';
  }

  for ($x = 0; $x <= 10; $x++) {
    $id = $start + $x;
    $information = scrape($id);
    if ($information) {
      console(implode(' - ', $information));
      $json = '{ ';
      $json = $json . keyValue('name', $information[0]) . ', ';
      $json = $json . keyValue('location', $information[1]) . ', ';
      $json = $json . keyValue('position', $information[2]);
      $json = $json . '}, ' . "\r\n";
      fwrite($file, $json);
    } else {
      console("Error: No record found for id (" . $id . ")");
    }
  }

  fwrite($file, ']' . "\r\n");
  fclose($file);

?>