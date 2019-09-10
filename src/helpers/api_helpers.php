<?php
  include 'config/secret.php';

  function createSearchUri($q) {
    // api parameters
    $maxResults = 50;
    $dateLimit = "2006-01-01T00%3A00%3A00Z";
    $q = rawurlencode($q);
    // $api = API_KEY;
    $api = getenv('API_KEY');

    return "https://www.googleapis.com/youtube/v3/search?"
      ."part=snippet"
      ."&maxResults=$maxResults"
      ."&order=relevance"
      ."&publishedBefore=$dateLimit"
      ."&q=$q"
      ."&type=video"
      ."&key=$api";
  }

  function callSearchApi($q) {
    $curl = curl_init();
    $url = createSearchUri($q);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
    $json = curl_exec($curl);
    curl_close($curl);

    return json_decode($json, true);
  }
?>