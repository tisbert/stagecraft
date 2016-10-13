<?php namespace Craft;

require_once __DIR__ . '/BaseStagecraftService.php';

class Stagecraft_GlobalsService extends BaseStagecraftService {

  public function export(array $sets) {
    $setDefs = array();

    foreach ($sets as $set) {
      $setDefs[$set->handle][] = array(
        'name' => $set->name,
        'fieldLayout' => $this->_exportFieldLayout($set->getFieldLayout())
      );
    }

    return $setDefs;
  }

  public function import($sets) {
    return new Stagecraft_ResultModel();
  }
}
