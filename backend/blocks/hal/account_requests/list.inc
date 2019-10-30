<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->account_requests
 * @param User  $this->user
 */
declare (strict_types=1);

$out = [
    '_links' => [
        'self' => ['href' => parent::generateUri('account_requests.index')]
    ],
    '_embedded' => [
        'account_requests' => []
    ]
];

$userCanView = parent::isAllowed('account_requests', 'view');
$userCanEdit = parent::isAllowed('account_requests', 'update');
foreach ($this->account_requests as $a) {
    $links = [];
    if ($userCanView) {
        $links['self'] = ['href'=> parent::generateUri('account_requests.view', ['id'=>$a->id])];
    }
    if ($userCanEdit) {
        $links['edit'] = ['href'=>parent::generateUri('account_requests.update', ['id'=>$a->id])];
    }
    if ($links) {
        $a->_links = $links;
    }

    $out['_embedded']['account_requests'][] = $a;
}


echo json_encode($out, JSON_PRETTY_PRINT);
