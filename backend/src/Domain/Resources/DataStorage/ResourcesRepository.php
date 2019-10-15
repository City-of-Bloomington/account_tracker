<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\DataStorage;
use Domain\Resources\Entities\ResourceEntity;

interface ResourcesRepository
{
    // Read functions
    public function load(int $id): ResourceEntity;
    public function find(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array;

    // Write functions
    public function save(ResourceEntity $resource): int;
}
