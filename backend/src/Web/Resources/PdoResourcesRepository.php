<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Resources;

use Aura\SqlQuery\Common\SelectInterface;
use Web\PdoRepository;
use Domain\Resources\DataStorage\ResourcesRepository;
use Domain\Resources\Entities\ResourceEntity;

class PdoResourcesRepository extends PdoRepository implements ResourcesRepository
{
    const TABLE = 'resources';

    public function columns(): array
    {
        static $columns;
        if (!$columns) { $columns = array_keys(get_class_vars('Domain\Resources\Entities\ResourceEntity')); }
        return $columns;
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from(self::TABLE);
        return $select;
    }

    public static function hydrate(array $row): ResourceEntity
    {
        $row['definition'] = json_decode($row['definition'], true);

        return new ResourceEntity($row);
    }

    public function load(int $id): ResourceEntity
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
        $select->where('id=?', $id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new ResourceEntity($result['rows'][0]);
        }
        throw new \Exception('resources/unknown');
    }

    /**
     * Look up resources using strict matching of fields
     */
    public function find(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select = $this->baseSelect();
        foreach ($this->columns() as $k) {
            if (array_key_exists($k, $fields)) {
                if (!empty($k)) {
                    $select->where("$k=?", $fields[$k]);
                }
                else {
                    $select->where("$k is null");
                }
            }
        }
        return parent::performHydratedSelect($select, __CLASS__.'::hydrate', $itemsPerPage, $currentPage);
    }

    /**
     * Saves a resource and returns the id for the resource
     */
    public function save(ResourceEntity $res): int
    {
        return parent::saveToTable(['id'         => $res->id,
                                    'code'       => $res->code,
                                    'name'       => $res->name,
                                    'type'       => $res->type,
                                    'definition' => json_encode($res->definition)
                                   ], self::TABLE);
    }
}
