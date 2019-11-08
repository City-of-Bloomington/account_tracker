<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->resources
 */
declare (strict_types=1);
use Web\Url;

$userCanView = parent::isAllowed('resources', 'view');
$userCanEdit = parent::isAllowed('resources', 'update');


$hal = [
    '_links' => [
        'self' => ['href' => Url::current_url(BASE_HOST)]
    ],
    '_embedded' => [
        'resources' => []
    ]
];
if ($userCanEdit) {
    $hal['_links']['add'] = ['href' => parent::generateUri('resources.update')];
}

if ($this->resources) {
    foreach ($this->resources as $r) {
        $links = [];
        if ($userCanView) {
            $links['self'] = ['href' => parent::generateUri('resources.view', ['id'=>$r->id])];
        }
        if ($userCanEdit) {
            $links['edit'] = ['href' => parent::generateUri('resources.update', ['id'=>$r->id])];
        }
        $r->_links = $links;
        $hal['_embedded']['resources'][] = $r;
    }
}

echo json_encode($hal, JSON_PRETTY_PRINT);
