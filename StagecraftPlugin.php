<?php namespace Craft;

class StagecraftPlugin extends BasePlugin {

  public function getName() {
    return 'Stagecraft';
  }

  public function getDescription() {
    return 'Import and export fields and sections to and from a Craft site.';
  }

  public function getVersion() {
    return '0.1.0';
  }

  public function getDeveloper() {
    return 'Emerson Stone';
  }

  public function getDeveloperUrl() {
    return 'http://www.emersonstone.com';
  }

  public function hasCpSection() {
    return false;
  }

  public function getSettingsUrl() {
    return 'stagecraft';
  }

  public function registerCpRoutes() {
    return array(
      'stagecraft' => array('action' => 'stagecraft/exportFields'),
      'stagecraft/version1' => array('action' => 'stagecraft/index'),
      'stagecraft/export/fields' => array('action' => 'stagecraft/exportFields'),
      'stagecraft/export/sections' => array('action' => 'stagecraft/exportSections'),
      'stagecraft/import/1' => array('action' => 'stagecraft/importStep1'),
    );
  }
}
