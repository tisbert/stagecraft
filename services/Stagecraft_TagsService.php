<?php namespace Craft;

require_once __DIR__ . '/BaseStagecraftService.php';

class Stagecraft_TagsService extends BaseStagecraftService {

  public function export(array $groups) {
    $groupDefs = array();

    foreach ($groups as $group) {
      $tagDefs = array();

      // foreach ( $group->getTags() as $tag ) {
      //   $tagDefs[] = $tag->title;
      // }

      $groupDefs[$group->handle][] = array(
        'name' => $group->name,
        'tags' => $tagDefs,
        'fieldLayout' => $this->_exportFieldLayout($group->getFieldLayout())
      );
    }

    return $groupDefs;
  }

  // TODO import tag groups
  public function import($groups) {
    return new Stagecraft_ResultModel();
  }
}
