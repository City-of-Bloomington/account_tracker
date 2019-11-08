<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->employees
 */
declare (strict_types=1);
use Web\Url;

$hal = [
    '_links' => [
        'self' => ['href' => Url::current_url(BASE_HOST)]
    ],
    '_embedded' => [
        'employees' => []
    ]
];
$userCanEdit = parent::isAllowed('employees', 'update');
if ($this->employees) {
    foreach ($this->employees as $e) {
        $e->_links = [
            'self' => ['href' => parent::generateUri('employees.view', ['id'=>$e->number])]
        ];
        $hal['_embedded']['employees'][] = $e;
    }
}
echo json_encode($hal, JSON_PRETTY_PRINT);
