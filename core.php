<?php
  class UtahsRight {
    // config vars
    public static $host = 'http://www.utahsright.com/salaries.php';
    // return all listings
    public static function all($page) {
      // load query for all police
      $query = "?city=all&query=police&page=" . $page;
      // data vars
      $names = array();
      // generate url to get html
      $url = self::$host.$query;
      // get html for document
      $doc = new DomDocument;
      $doc->validateOnParse = true;
      @$doc->loadHtml(file_get_contents($url));
      $spans = $doc->getElementsByTagName('span');
      // clean the names
      foreach ($spans as $span) {
          $span = $span->nodeValue;
          if ($span[0] == " ") {
            $span = ltrim($span, " ");
          }
          if ($span !== "***name Redacted**") {
            array_push($names, $span);
          }
      }
      // remove navigation
      unset($names[count($names)-1]);
      // return results
      foreach ($names as $name) {
        echo $name . "\n";
      }
    }
  }
  // testing
  UtahsRight::all(1);
?>