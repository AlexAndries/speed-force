<?php

/**
 * Project: SpeedForce
 * User: Alex Andries <alexandru.andries@outlook.com>
 */
class GooglePageInsights extends GoogleAuth {

  private $pageInsightsClient;

  private $results = array();

  private $url;

  public function __construct() {
    parent::__construct();

    $this->client->setApplicationName('Page Speed Insights');
    $this->pageInsightsClient = new Google_Service_Pagespeedonline($this->client);
    $this->results = new GooglePageInsightsResult();
  }

  public function runPageSpeedTest($url) {
    $this->url = $url;
    $this->runMobileSpeedTest();
    $this->runDesktopSpeedTest();
    $this->saveReport();
  }

  private function runMobileSpeedTest() {
    sleep(1); // wait for google api

    $fields = array(
      'strategy' => 'mobile'
    );
    $result = $this->pageInsightsClient->pagespeedapi->runpagespeed($this->url, $fields);
    $this->parseResults($result, 'mobile');
  }

  private function runDesktopSpeedTest() {
    sleep(1); // wait for google api

    $fields = array(
      'strategy' => 'desktop'
    );
    $result = $this->pageInsightsClient->pagespeedapi->runpagespeed($this->url, $fields);
    $this->parseResults($result, 'desktop');
  }

  private function saveReport() {
    global $wpdb;

    $wpdb->insert($wpdb->prefix . SpeedForce::PAGE_INSIGHTS_TABLE, array(
      'url'             => $this->url,
      'mobile_speed'    => $this->results->getMobileSpeed(),
      'desktop_speed'   => $this->results->getDesktopSpeed(),
      'ui_score'        => $this->results->getUi(),
      'loading_mobile'  => json_encode($this->results->getLoading('mobile')),
      'loading_desktop' => json_encode($this->results->getLoading('desktop')),
      'rules_mobile'    => json_encode($this->results->getRules('mobile')),
      'rules_desktop'   => json_encode($this->results->getRules('desktop')),
      'rules_ui'        => json_encode($this->results->getRules('ui'))
    ));
  }

  private function parseResults(Google_Service_Pagespeedonline_Result $result, $type) {
    var_dump($result);
    exit();
    if ($result->getResponseCode() !== 200) {
      throw new Exception('Error on $result');
    }

    switch ($type) {
      case 'mobile':
        $this->results->setMobileSpeed($result->ruleGroups['SPEED']->score);
        $this->results->setUi($result->ruleGroups['USABILITY']->score);
        break;

      case 'desktop':
        $this->results->setDesktopSpeed($result->ruleGroups['SPEED']->score);
        break;
    }

    $pageStats = $result->getPageStats();

    $this->results->setLoading(array(
      'cssSize'              => SpeedForceAdmin::convertBytesTo($pageStats->cssResponseBytes),
      'htmlSize'             => SpeedForceAdmin::convertBytesTo($pageStats->htmlResponseBytes),
      'imagesSize'           => SpeedForceAdmin::convertBytesTo($pageStats->imageResponseBytes),
      'javascriptSize'       => SpeedForceAdmin::convertBytesTo($pageStats->javascriptResponseBytes),
      'cssFiles'             => $pageStats->numberCssResources,
      'javascriptFiles'      => $pageStats->numberJsResources,
      'resourcesFiles'       => $pageStats->numberResources,
      'staticResourcesFiles' => $pageStats->numberStaticResources,
      'totalRequest'         => SpeedForceAdmin::convertBytesTo($pageStats->totalRequestBytes)
    ), $type);

    $formattedResults = $result->getFormattedResults();

    if ($formattedResults->getRuleResults() && is_array($formattedResults->getRuleResults()) && !empty($formattedResults->getRuleResults())) {
      foreach ($formattedResults->getRuleResults() as $rule => $data) {
        $location = $type;
        if ($data->groups[0] == 'USABILITY') {
          $location = 'ui';
        }

        $this->results->setRule($rule, array(
          'ruleName' => $data->localizedRuleName,
          'impact'   => $data->ruleImpact
        ), $location);
      }
    }
  }
}