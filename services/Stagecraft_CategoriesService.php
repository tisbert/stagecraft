<?php namespace Craft;

require_once __DIR__ . '/BaseStagecraftService.php';

class Stagecraft_CategoriesService extends BaseStagecraftService {

  public function export(array $groups) {
    $groupDefs = array();

    foreach ($groups as $group) {
      $categoryDefs = array();

      // foreach ( $group->categories as $category ) {
      //   $categoryDefs[] = $category->title;
      // }

      $categoryGroupLocaleDefs = array();

      foreach ( $group->getLocales() as $locale ) {
        $categoryGroupLocaleDefs[] = array(
          'locale' => $locale->locale,
          'urlFormat' => $locale->urlFormat,
          'nestedUrlFormat' => $locale->nestedUrlFormat
        );
      }

      $groupDefs[$group->handle][] = array(
        'name' => $group->name,
        'hasUrls' => $group->hasUrls,
        'template' => $group->template,
        'maxLevels' => $group->maxLevels,
        'categories' => $categoryDefs,
        'fieldLayout' => $this->_exportFieldLayout($group->getFieldLayout())
      );
    }

    return $groupDefs;
  }

  public function import($groups) {
    return new Stagecraft_ResultModel();
  }
}
