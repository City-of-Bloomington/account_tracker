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
    /**
     * Maps response fieldnames to the names used in the database
     */
    public static $fieldmap = [
        'id'                 => ['prefix'=>'a', 'dbName'=>'id'             ],
        'requester_id'       => ['prefix'=>'a', 'dbName'=>'requester_id'   ],
        'employee_number'    => ['prefix'=>'a', 'dbName'=>'employee_number'],
        'type'               => ['prefix'=>'a', 'dbName'=>'type'           ],
        'status'             => ['prefix'=>'a', 'dbName'=>'status'         ],
        'created'            => ['prefix'=>'a', 'dbName'=>'created'        ],
        'modified'           => ['prefix'=>'a', 'dbName'=>'modified'       ],
        'completed'          => ['prefix'=>'a', 'dbName'=>'completed'      ],
        'employee'           => ['prefix'=>'a', 'dbName'=>'employee'       ],
        'resources'          => ['prefix'=>'a', 'dbName'=>'resources'      ],
        'requester_username' => ['prefix'=>'p', 'dbName'=>'username'       ]
    ];

    public function columns(): array
    {
        static $cols = [];
        if (!$cols) {
            foreach (self::$fieldmap as $responseName=>$map) {
                $cols[] = "$map[prefix].$map[dbName] as $responseName";
            }
        }
        return $cols;
    }

    public function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from(self::TABLE.' a')
               ->join('INNER', 'people p', 'a.requester_id=p.id');
        return $select;
    }

    public static function hydrate(array $row): AccountRequest
    {
        return new AccountRequest($row);
    }

    public function load(int $id): AccountRequest
    {
        $select = $this->baseSelect();
        $select->where('a.id=?', $id);
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
                $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                $select->where("$column=?", $req->$f);
            }
        }
        return parent::performHydratedSelect($select,
                                             __CLASS__.'::hydrate',
                                             self::$DEFAULT_SORT,
                                             $req->itemsPerPage,
                                             $req->currentPage);
    }

    /**
     * Save to the database and return the ID of the account_request
     */
    public function save(AccountRequest $r): int
    {
        // Strip out all the timestamp fields. Let the database handle
        // setting all the timestamps appropriately.
        $data = [
            'requester_id'    => $r->requester_id,
            'employee_number' => $r->employee_number,
            'type'            => $r->type,
            'status'          => $r->status,
            'employee'        => json_encode($r->employee),
            'resources'       => json_encode($r->resources)
        ];
        if ($r->id) { $data['id'] = $r->id; }
        return parent::saveToTable($data, self::TABLE);
    }
}
