<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param InfoResponse $this->infoResponse
 */
declare (strict_types=1);

$hal = $this->infoResponse->employee;
$hal->_links = [
    'self' => ['href' => parent::generateUri('employees.view', ['id'=>$this->infoResponse->employee->number])]
];
$hal->_embedded = [
    'resources' => []
];

$userCanView = parent::isAllowed('resources', 'view');
$userCanEdit = parent::isAllowed('resources', 'edit');
foreach ($this->infoResponse->resources as $r) {
    $links = [];
    if ($userCanView) {
        $links['self'] = ['href' => parent::generateUri('resources.view', ['id'=>$r['definition']->id])];
    }
    if ($userCanEdit) {
        $links['edit'] = ['href' => parent::generateUri('resources.update', ['id'=>$r['definition']->id])];
    }
    $r['_links'] = $links;
    $hal->_embedded['resources'][] = $r;
}
$hal->errors = $this->infoResponse->errors;

echo json_encode($hal, JSON_PRETTY_PRINT);
