<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param InfoResponse $this->response
 */
declare (strict_types=1);
$userCanView = parent::isAllowed('profiles', 'view');
$userCanEdit = parent::isAllowed('profiles', 'update');

$hal = [
    '_links' => [
        'self' => ['href' => parent::generateUri('profiles.index')]
    ],
    '_embedded' => [
        'profiles' => []
    ]
];
if ($userCanEdit) {
    $hal['_links']['add'] = ['href'=>parent::generateUri('profiles.update')];
}

foreach ($this->response->profiles as $p) {
    $links = [];
    if ($userCanView) {
        $links['self'] = ['href'=>parent::generateUri('profiles.view', ['id'=>$p->id])];
    }
    if ($userCanEdit) {
        $links['edit'] = ['href' => parent::generateUri('profiles.update', ['id'=>$p->id])];
    }
    $p->_links = $links;

    $hal['_embedded']['profiles'][] = $p;
}


echo json_encode($hal, JSON_PRETTY_PRINT);
