<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param ResourceEntity $this->resourceEntity
 */
declare (strict_types=1);
$links = [
    'self' => ['href' => parent::generateUri('resources.view', ['id'=>$this->resourceEntity->id])]
];
if (parent::isAllowed('resources', 'update')) {
    $links['edit'] = ['href' => parent::generateUri('resources.update', ['id'=>$this->resourceEntity->id])];
}
$this->resourceEntity->_links = $links;

echo json_encode($this->resourceEntity, JSON_PRETTY_PRINT);
