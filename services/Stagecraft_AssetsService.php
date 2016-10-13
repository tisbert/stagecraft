<?php namespace Craft;

require_once __DIR__ . 'BaseStagecraftService.php';

class Stagecraft_AssetsService extends BaseStagecraftService {

  public function import($assets) {
    return new Stagecraft_ResultModel();
  }

  public function export() {
    //
  }
}
