<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Profiles;

use Domain\Profiles\DataStorage\ProfilesRepository;
use Domain\Profiles\Entities\Profile;
use Domain\Profiles\UseCases\Search\Request as SearchRequest;
use Domain\Profiles\UseCases\Update\Request as UpdateRequest;
use Web\PdoRepository;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;

class PdoProfilesRepository extends PdoRepository implements ProfilesRepository
{
    const TABLE = 'profiles';
    public static $DEFAULT_SORT = ['code'];

    public function columns()
    {
        static $columns;
        if (!$columns) { $columns = array_keys(get_class_vars('Domain\Profiles\Entities\Profile')); }
        return $columns;
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
        return $select;
    }

    public static function hydrate(array $row): Profile
    {
        return new Profile($row);
    }

    public function load(int $id): Profile
    {
        $sql = 'select * from profiles where id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$id]);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return self::hydrate($result[0]);
    }

    /**
     * Look up profiles using exact matching of fields
     */
    public function find(): array
    {
        $select = $this->baseSelect();
        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f=?", $req->$f);
            }
        }
        return parent::performHydratedSelect($select,
                                             __CLASS__.'::hydrate',
                                             self::$DEFAULT_SORT);
    }

    public function save(UpdateRequest $request): int
    {
        $data = (array)$request;
        return parent::saveToTable($data, self::TABLE);
    }
}
