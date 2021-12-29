<?php

namespace App\Helper;

use Exception;
use Illuminate\Support\Facades\Http;

class Helper
{
  static function test()
  {
    return 'test';
  }

  static function sendNotification(
    $to,
    $notificationTitle = "Magenta HRD",
    $notificationBody = "Notification from Magenta HRD apps",
    $notificationImage = null,
    $data = null
  ) {
    try {
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'key=' . env('FCM_SERVER_TOKEN'),
      ])->post('https://fcm.googleapis.com/fcm/send', [
        "to" => $to,
        "notification" => [
          "title" => $notificationTitle,
          "body" => $notificationBody,
          "image" => $notificationImage,
        ],
        "data" => $data,
      ]);

      return $response;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  static function prettyMonth($month = 0, $locale = 'en')
  {
    $enMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Desember'];

    $idMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    if ($locale == 'id') {
      return $idMonths[$month];
    }

    return $enMonths[$month];
  }
}
