<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GooglePageInsights extends GoogleAuth {

  private $pageInsightsClient;

  public function __construct() {
    parent::__construct();

    $this->client->setApplicationName('Page Speed Insights');
    $this->pageInsightsClient = new Google_Service_Pagespeedonline($this->client);
  }

  public function runPageSpeedTest() {
    $fields = array(
      'strategy'   => 'mobile',
      'screenshot' => 'true'
    );
    $result = $this->pageInsightsClient->pagespeedapi->runpagespeed('https://www.google.ro', $fields);
    var_dump($result->getResponseCode());
  }
}