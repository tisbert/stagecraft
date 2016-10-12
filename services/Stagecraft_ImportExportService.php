<?php namespace Craft;

class Stagecraft_ImportExportService extends BaseApplicationComponent {
  /**
   * @param string $json
   * @return Stagecraft_ResultModel
   */
  public function importFromJson($json) {
    $exportedDataModel = Stagecraft_ExportedDataModel::fromJson($json);

    return $this->importFromExportedDataModel($exportedDataModel);
  }

  public function importTabsFromJson($json, $applyTo) {
    $exportedDataModel = Stagecraft_ExportedDataModel::fromJson($json);
    $applyToModel = json_decode($applyTo, false);

    return $this->importTabsFromExportedDataModel($exportedDataModel, $applyToModel);
  }

  public function loadFromJson($json) {
    $data = Stagecraft_ExportedDataModel::fromJson($json);

    foreach ($data->fields as $group) {
      $group['notes'] = "HEY";
    }

    return $data;
  }

  /**
   * @param $array
   * @return Stagecraft_ResultModel
   */
  public function importFromArray($array) {
    $exportedDataModel = new Stagecraft_ExportedDataModel($array);

    return $this->importFromExportedDataModel($exportedDataModel);
  }

  /**
   * @param $model
   * @return Stagecraft_ResultModel
   */
  private function importTabsFromExportedDataModel($model, $applyTo) {
    $result = new Stagecraft_ResultModel();

    if ($model !== null) {
      $contentTabsImportResult = craft()->stagecraft_contentTabs->import($model->contenttabs, $applyTo);

      //$result->consume($contentTabsImportResult);
    }

    return $result;
  }

  private function importFromExportedDataModel($model) {
    $result = new Stagecraft_ResultModel();

    if ($model !== null) {
      $assetImportResult = craft()->stagecraft_assets->import($model->assets);
      $categoryImportResult = craft()->stagecraft_categories->import($model->categories);
      $fieldImportResult = craft()->stagecraft_fields->import($model->fields);
      $globalImportResult = craft()->stagecraft_globals->import($model->globals);
      $sectionImportResult = craft()->stagecraft_sections->import($model->sections);
      $tagImportResult = craft()->stagecraft_tags->import($model->tags);

      $result->consume($assetImportResult);
      $result->consume($categoryImportResult);
      $result->consume($fieldImportResult);
      $result->consume($globalImportResult);
      $result->consume($sectionImportResult);
      $result->consume($tagImportResult);
    }

    return $result;
  }
}
