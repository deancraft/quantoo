<?php

namespace Drupal\web_custom\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class WebCustomCommands extends DrushCommands {

  /**
   * @var \Drupal\web_custom\CustomService
   */
  protected $customManager;

  /**
   * @param  \Drupal\web_custom\CustomService  $customManager
   */
  public function __construct(\Drupal\web_custom\CustomService $customManager) {
    parent::__construct();
    $this->customManager = $customManager;
  }

  /**
   * Command description here.
   *
   * @param $nodeType
   *   Type of Node.
   * @usage web_custom-commandName foo
   *   Usage description
   *
   * @command web_custom:commandName
   * @aliases foo
   */
  public function commandName($nodeType) {
    $result = $this->customManager->saveFileWithNodeDetails($nodeType);
    if ($result) {
      $this->logger()->success(dt('The file has been saved'));
    } else {
      $this->logger()->error(dt('The file has not been saved'));
    }
  }
}
