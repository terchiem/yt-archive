<?php
  function getThumbnail($id) {
    return "https://i.ytimg.com/vi/${id}/mqdefault.jpg";
  }

  function getStringDate($date) {
    $dateObj = date_create($date);
    return date_format($dateObj, "M j, Y");
  }

?>