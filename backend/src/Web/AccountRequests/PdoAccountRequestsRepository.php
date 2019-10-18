<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\AccountRequests;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\AccountRequests\Entities\AccountRequest;
use Domain\AccountRequests\DataStorage\AccountRequestsRepository;
use Domain\AccountRequests\UseCases\Search\Request as SearchRequest;
use Web\PdoRepository;

class PdoAccountRequestsRepository extends PdoRepository implements AccountRequestsRepository
{
    const TABLE = 'account_requests';

    public static $DEFAULT_SORT = ['modified desc'];
    public function columns(): array
    {
        static $columns;
        if (!$columns) {
             $columns = array_keys(get_class_vars('Domain\AccountRequests\Entities\AccountRequest'));
        }
        return $columns;
    }

    public function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
        return $select;
    }

    public static function hydrate(array $row): AccountRequest
    {
        return new AccountRequest($row);
    }

    public function load(int $id): AccountRequest
    {
        $select = $this->baseSelect();
        $select->where('id=?', $id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrate($result['rows'][0]);
        }
        throw new \Exception('account_requests/unknown');
    }

    /**
     * Look up account requests using strict matching of fields
     */
    public function find(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f=?", $req->$f);
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        return parent::performHydratedSelect($select, __CLASS__.'::hydrate', $req->itemsPerPage, $req->currentPage);
    }
}
