<?php namespace Craft;

// TODO comment file

class StagecraftController extends BaseController {

  public function actionIndex() {
    $this->renderTemplate('stagecraft/_index', array(
      'groupOptions'     => $this->_getGroupOptions(),
      'entryTypeOptions' => $this->_getSectionOptions(),
    ));
  }

  public function actionImportStep1() {
    $this->renderTemplate('stagecraft/_import1', array());
  }

  public function actionImportStep2() {
    $this->requirePostRequest();

    $json = craft()->request->getParam('data', '{}');

    $result = craft()->stagecraft_importExport->loadFromJson($json);

    $this->renderTemplate('stagecraft/_import2', array(
      'model' => $result,
      'rawdata' => $json
    ));
  }

  public function actionImportStep3() {
    $this->requirePostRequest();

    $json = craft()->request->getParam('data', '{}');

    $result = craft()->stagecraft_importExport->importFromJson($json);

    if ($result->ok) {
      $this->renderTemplate('stagecraft/_import3', array());
    }

    craft()->userSession->setError(implode(', ', $result->errors));
  }

  public function actionExportFields() {
    $this->renderTemplate('stagecraft/_fieldExport', array(
      'GroupOptions' => $this->_getGroupOptions(),
    ));
  }

  public function actionExportSections() {
    $this->renderTemplate('stagecraft/_sectionExport', array(
      'entryTypeOptions' => $this->_getSectionOptions(),
    ));
  }

  public function actionImport() {
    $this->requirePostRequest();

    $json = craft()->request->getParam('data', '{}');

    $result = craft()->stagecraft_importExport->importFromJson($json);

    if ($result->ok) {
      craft()->userSession->setNotice('All done.');
      $this->redirectToPostedUrl();
      return;
    }

    craft()->userSession->setError(implode(', ', $result->errors));

    craft()->urlManager->setRouteVariables(array(
      'groupOptions' => $this->_getGroupOptions(),
      'entryTypeOptions' => $this->_getSectionOptions()
    ));
  }

  public function actionFieldExport() {
    $this->requirePostRequest();

    $result = new Stagecraft_ExportedDataModel(array(
      'assets' => [],
      'categories' => [],
      'fields' => $this->_exportFields(),
      'globals' => [],
      'sections' => [],
      'tags' => []
    ));

    $json = $result->toJson();

    if (craft()->request->getParam('download')) {
      HeaderHelper::setDownload('stagecraft.json', strlen($json));
    }

    JsonHelper::sendJsonHeaders();
    echo $json;
    craft()->end();
  }

  public function actionSectionExport() {
    $this->requirePostRequest();

    $section_ids = craft()->request->getParam('selectedSections', '*');

    if ($section_ids == '*') {
      $sections = craft()->sections->getAllSections();
    } else {
      $sections = array();

      if ( is_array($section_ids) ) {
        foreach ($section_ids as $id) {
          $sections[] = craft()->section->getSectionById($id);
        }
      }
    }

    $result = new Stagecraft_ExportedDataModel(array(
      'assets'     => [],
      'categories' => [],
      'fields'     => $this->_exportFields(),
      'globals'    => [],
      'sections'   => $this->_exportSections($sections),
      'tags'       => []
    ));

    $json = $result->toJson();

    if (craft()->request->getParam('download')) {
      HeaderHelper::setDownload('stagecraft.json', strlen($json));
    }

    JsonHelper::sendJsonHeaders();
    echo $json;
    craft()->end();
  }

  // TODO create button for exporting err'thang
  public function actionExport() {
    $this->requirePostRequest();

    $result = new Stagecraft_ExportedDataModel(array(
      'assets' => $this->_exportAssets(),
      'categories' => $this->_exportCategories(),
      'fields' => $this->_exportFields(),
      'globals' => $this->_exportGlobals(),
      'sections' => $this->_exportSections(),
      'tags' => $this->_exportTags()
    ));

    $json = $result->toJson();

    if (craft()->request->getParam('download')) {
      HeaderHelper::setDownload('stagecraft.json', strlen($json));
    }

    JsonHelper::sendJsonHeaders();
    echo $json;
    craft()->end();
  }

  private function _getGroupOptions() {
    $groupOptions = array();

    foreach (craft()->fields->getAllGroups() as $group) {
      $groupOptions[$group->id] = $group->name;
    }

    return $groupOptions;
  }

  private function _getSectionOptions() {
    foreach (craft()->sections->getAllSections() as $section) {
      $entryTypeOptions[$section->id] = $section->name;
    }

    return $entryTypeOptions;
  }

  private function _exportAssets() {
    return array();
  }

  private function _exportCategories() {
    return array();
  }

  private function _exportFields() {
    $selectedIds = craft()->request->getParam('selectedGroups', '*');

    if ($selectedIds == '*') {
      $groups = craft()->fields->getAllGroups();
    } else {
      $groups = array();

      if (is_array($selectedIds)) {
        foreach ($selectedIds as $id) {
          $groups[] = craft()->fields->getGroupById($id);
        }
      }
    }

    return craft()->stagecraft_fields->export($groups);
  }

  private function _exportGlobals() {
    return array();
  }

  private function _exportSections($sections = array()) {
    if (empty($sections)) {
      $sections = craft()->sections->getAllSections();
    }

    return craft()->stagecraft_sections->export($sections);
  }

  private function _exportTags() {
    return array();
  }
}
