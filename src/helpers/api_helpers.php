<?php
  function searchListAPI($q, $search = true, $token = NULL) {
    $searchUri = createSearchUri($q, $search, $token);
    return callAPI($searchUri);
  }

  function videoListAPI($searchResponse) {
    if (empty($searchResponse['error'])) {
      $videoUri = createVideoUri($searchResponse);
      return callAPI($videoUri);
    } else {
      return $searchResponse;
    }
  }

  // Private
  function getVideoIds($responseJson) {
    $videoIds = [];
    foreach ($responseJson['items'] as $item) {
      $videoIds[] = $item['id']['videoId'];
    }
    return join("%2C", $videoIds);
  }

  function extractPageToken($responseJson) {
    return $responseJson['nextPageToken'] ?? NULL;
  }

  function createVideoUri($responseJson) {
    $videoIds = getVideoIds($responseJson);
    $api = API_KEY; 

    return "https://www.googleapis.com/youtube/v3/videos?"
      ."part=snippet%2Cstatistics%2CcontentDetails"
      ."&fields=items(id%2Csnippet(publishedAt%2CchannelId%2Ctitle%2Cdescription%2CchannelTitle%2CcategoryId%2Ctags)%2Cstatistics(viewCount%2ClikeCount%2CdislikeCount)%2CcontentDetails(duration))"
      ."&id=$videoIds"
      ."&key=$api";
  }

  function createSearchUri($q, $search, $token) {
    // api parameters
    $searchBy = $search ? "&q=".rawurlencode($q) : 
      "&videoCategoryId=$q";
    $maxResults = 50;
    $dateLimit = "2006-01-01T00%3A00%3A00Z";
    $q = rawurlencode($q);
    $api = API_KEY;
    $page_token = $token ? "&pageToken=$token" : "";

    return "https://www.googleapis.com/youtube/v3/search?"
      ."part=snippet"
      ."&fields=items(id(videoId))%2CnextPageToken"
      ."&type=video"
      ."&order=relevance"
      ."&maxResults=$maxResults"
      ."&publishedBefore=$dateLimit"
      .$searchBy
      ."&key=$api"
      .$page_token;
  }

  function callAPI($uri) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $uri);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
    $json = curl_exec($curl);
    curl_close($curl);

    return json_decode($json, true);
  } 
?>