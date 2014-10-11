<?php

/**
 * This file is automatically generated. Use 'arc liberate' to rebuild it.
 *
 * @generated
 * @phutil-library-version 2
 */
phutil_register_library_map(array(
  '__library_version__' => 2,
  'class' => array(
    'BurndownActionMenuEventListener' => 'src/BurndownActionMenuEventListener.php',
    'BurndownApplication' => 'src/BurndownApplication.php',
    'BurndownController' => 'src/BurndownController.php',
    'BurndownData' => 'src/BurndownData.php',
    'BurndownDataDate' => 'src/BurndownDataDate.php',
    'BurndownException' => 'src/BurndownException.php',
    'BurndownListController' => 'src/BurndownListController.php',
    'BurndownTestDataGenerator' => 'src/BurndownTestDataGenerator.php',
    'SprintConstants' => 'src/SprintConstants.php',
    'SprintStartDateField' => 'src/SprintStartDateField.php',
    'SprintEndDateField' => 'src/SprintEndDateField.php',
    'SprintProjectCustomField' => 'src/SprintProjectCustomField.php',
    'SprintReportController' => 'src/SprintReportController.php',
    'SprintTaskStoryPointsField' => 'src/SprintTaskStoryPointsField.php',
  ),
  'function' => array(),
  'xmap' => array(
    'BurndownActionMenuEventListener' => 'PhabricatorEventListener',
    'BurndownApplication' => 'PhabricatorApplication',
    'BurndownController' => 'PhabricatorController',
    'BurndownException' => 'Exception',
    'BurndownListController' => 'PhabricatorController',
    'BurndownTestDataGenerator' => 'PhabricatorTestDataGenerator',
    'SprintConstants' => 'ManiphestConstants',
    'SprintStartDateField' => 'SprintProjectCustomField',
    'SprintEndDateField' => 'SprintProjectCustomField',
    'SprintProjectCustomField' => array(
      'PhabricatorProjectCustomField',
      'PhabricatorStandardCustomFieldInterface',
    ),
    'SprintReportController' => 'ManiphestController',
    'SprintTaskStoryPointsField' => array(
    'ManiphestCustomField',
    'PhabricatorStandardCustomFieldInterface',
    ),
  ),
));
