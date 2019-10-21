<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Employees;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use Domain\Employees\DataStorage\EmployeesRepository;
use Domain\Employees\Entities\Employee;
use Domain\Employees\UseCases\Search\Request as SearchRequest;
use Web\PdoRepository;

class PdoEmployeesRepository extends PdoRepository implements EmployeesRepository
{
    const UDFAttributeID = 52;
    const TableID        = 66;
    const StatusID       = 258;

    public static $DEFAULT_SORT = ['lastname', 'firstname'];

    public function __construct(\PDO $pdo)
    {
        $this->pdo          = $pdo;
        $this->queryFactory = new QueryFactory('Sqlsrv');
    }

    private function baseSelect(): SelectInterface
    {
        $attrId  = self::UDFAttributeID;
        $tableId = self::TableID;
        $status  = self::StatusID;

        $select = $this->queryFactory->newSelect();
        $select->cols(['e.EmployeeId                   as employeeID',
                       'e.EmployeeNumber               as employeeNum',
                       'n.EmployeeNameId               as employeeNameID',
                       'e.EmployeeName                 as name',
                       'e.LastName                     as lastname',
                       'e.FirstName                    as firstname',
                       'e.OrgStructureDescconcatenated as department',
                       'udf.ValString                  as username'])
               ->from(         'HR.vwEmployeeInformation     e')
               ->join('INNER', 'HR.EmployeeName              n',   'e.EmployeeId=n.EmployeeId and GETDATE() between n.EffectiveDate and n.EffectiveEndDate')
               ->join('LEFT',  'dbo.UDFEntry                 udf', "n.EmployeeNameId=udf.AttachedFKey and udf.UDFAttributeID=$attrId and udf.TableID=$tableId")
               ->where("e.vsEmploymentStatusId=$status");
        return $select;
    }

    public static function hydrate(array $row): Employee
    {
        return new Employee([
            'number'     => $row['employeeNum'],
            'firstname'  => $row['firstname'  ],
            'lastname'   => $row['lastname'   ],
            'department' => $row['department' ],
            'username'   => $row['username'   ]
        ]);
    }

    public function load(int $number): Employee
    {
        $attrId  = self::UDFAttributeID;
        $tableId = self::TableID;
        $status  = self::StatusID;

        $sql = "select  e.EmployeeNumber               as employeeNum,
                        e.EmployeeName                 as name,
                        e.LastName                     as lastname,
                        e.FirstName                    as firstname,
                        e.OrgStructureDescconcatenated as department,
                        udf.ValString                  as username
                from HR.vwEmployeeInformation     e
                join HR.EmployeeName              n    on e.EmployeeId=n.EmployeeId
                                                        and GETDATE() between   n.EffectiveDate     and   n.EffectiveEndDate
                left join dbo.UDFEntry            udf  on n.EmployeeNameId=udf.AttachedFKey and udf.UDFAttributeID=$attrId and udf.TableID=$tableId
                where e.EmployeeNumber=$number";
        $query  = $this->pdo->query($sql);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return self::hydrate($result[0]);
    }

    /**
     * Look for people using wildcard matching of fields
     */
    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();


        if (!empty($req->firstname)) { $select->where("lower(e.FirstName) like ?", strtolower($req->firstname).'%'); }
        if (!empty($req->lastname )) { $select->where("lower(e.LastName ) like ?", strtolower($req->lastname ).'%'); }

        return parent::performHydratedSelect($select,
                                             __CLASS__.'::hydrate',
                                             self::$DEFAULT_SORT,
                                             $req->itemsPerPage,
                                             $req->currentPage);
    }
}
