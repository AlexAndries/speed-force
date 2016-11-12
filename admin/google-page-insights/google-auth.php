<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GoogleAuth {

  protected $client;

  public function __construct() {
    $this->client = new Google_Client();
    $this->client->setDeveloperKey(SPEED_FORCE_GOOGLE_API_KEY);

    if (!SPEED_FORCE_USE_SSL) {
      $guzzleClient = new GuzzleHttp\Client(array(
        'curl'   => array(CURLOPT_SSL_VERIFYPEER => false),
        'verify' => false
      ));
      $this->client->setHttpClient($guzzleClient);
    }
  }
}