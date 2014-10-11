<?php
/**
 * Copyright (C) 2014 Michael Peters
 * Licensed under GNU GPL v3. See LICENSE for full details
 */

final class BurndownListController extends PhabricatorController {

  private $view;

  public function processRequest() {

    $request = $this->getRequest();
    $viewer = $request->getUser();

    $nav = $this->buildNavMenu();
    $projects = $this->loadAllProjects($viewer);

    $order = $request->getStr('order', 'name');
    list($order, $reverse) = AphrontTableView::parseSort($order);

    $rows = array();
    foreach ($projects as $project) {

      $aux_fields = $this->getAuxFields($project, $viewer);
      $start = $this->getStartDate($aux_fields);
      $end = $this->getEndDate($aux_fields);

      $row = array();
      $row[] =  phutil_tag(
          'a',
          array(
            'href'  => '/sprint/view/'.$project->getId(),
            'style' => 'font-weight:bold',
          ),
          $project->getName());
      $row[] = phabricator_datetime($start, $viewer);
      $row[] = phabricator_datetime($end, $viewer);

      switch ($order) {
        case 'Name':
          $row['sort'] = $project->getName();
          break;
        case 'Start':
          $row['sort'] = $start;
          break;
        case 'End':
          $row['sort'] = $end;
          break;
        case 'name':
        default:
          $row['sort'] = $project->getName();
          break;
      }

    $rows[] = $row;
    }

    $rows = isort($rows, 'sort');
    foreach ($rows as $k => $row) {
      unset($rows[$k]['sort']);
    }
    if ($reverse) {
      $rows = array_reverse($rows);
    }

    $projects_table = id(new AphrontTableView($rows))
        ->setHeaders(
            array(
                'Sprint Name',
                'Start Date',
                'End Date',
            ))
        ->setColumnClasses(
            array(
                'left',
                'left narrow',
                'left narrow',
            ))
        ->makeSortable(
            $request->getRequestURI(),
                'order',
            $order,
            $reverse,
            array(
                'Name',
                'Start',
                'End',
            ));


    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Burndown List'));


    $help = id(new PHUIBoxView())
      ->appendChild(phutil_tag('p', array(),
          "To have a project show up in this list, make sure it's name includes"
          ."\"§\" and then edit it to set the start and end date."
      ))
      ->addMargin(PHUI::MARGIN_LARGE);

    $box= id(new PHUIBoxView())
      ->appendChild($projects_table)
      ->addMargin(PHUI::MARGIN_LARGE);

    $nav->appendChild(
        array(
            $crumbs,
            $help,
            $box,
        ));

    return $this->buildApplicationPage(

      array(
        $nav,
      ),
      array(
        'title' => array(pht('Sprint List')),
        'device' => true,
      ));
  }

  private function buildNavMenu() {
    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI('/sprint/report/'));
    $nav->addLabel(pht('Sprint Projects'));
    $nav->addFilter('list', pht('List'));
    $nav->addLabel(pht('Open Tasks'));
    $nav->addFilter('project', pht('By Project'));
    $nav->addFilter('user', pht('By User'));
    $nav->addLabel(pht('Burndown'));
    $nav->addFilter('burn', pht('Burndown Rate'));

    $this->view = $nav->selectFilter($this->view, 'list');

    return $nav;
  }

  // Load all projects with "§" in the name.
  private function loadAllProjects($viewer) {
    $projects = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->withDatasourceQuery(SprintConstants::MAGIC_WORD)
      ->execute();
    return $projects;
  }

  private function getAuxFields($project, $viewer) {
    // We need the custom fields so we can pull out the start and end date
    // TODO: query in a loop is bad
    $field_list = PhabricatorCustomField::getObjectFields(
        $project,
        PhabricatorCustomField::ROLE_EDIT);
    $field_list->setViewer($viewer);
    $field_list->readFieldsFromStorage($project);
    $aux_fields = $field_list->getFields();
    return $aux_fields;
  }

  private function getStartDate($aux_fields) {
    $start = idx($aux_fields, 'isdc:sprint:startdate')
        ->getProxy()->getFieldValue();
    return $start;
  }

  private function getEndDate($aux_fields) {
    $end = idx($aux_fields, 'isdc:sprint:enddate')
        ->getProxy()->getFieldValue();
    return $end;
  }
}
