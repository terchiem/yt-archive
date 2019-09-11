<?php
  function getThumbnail($id) {
    return "https://i.ytimg.com/vi/${id}/mqdefault.jpg";
  }

  function getStringDate($date) {
    $dateObj = date_create($date);
    return date_format($dateObj, "M j, Y");
  }

  function formatDuration($duration) {
    $di = new DateInterval($duration);
    $h = $di->h ? $di->h.':' : '';
    $m = str_pad($di->i, 2, '0', STR_PAD_LEFT).':';
    $s = str_pad($di->s, 2, '0', STR_PAD_LEFT);
    return $h.$m.$s;
  }

  function roundViews($views) {
    if ($views < 1000) {
      return $views;
    } elseif ($views < 1000000) {
      return round($views / 1000, 1) . 'K';
    } elseif ($views < 1000000000) {
      return round($views / 1000000, 1) . 'M';
    } else {
      return round($views / 1000000000, 1) . 'B';
    }
  }

?>