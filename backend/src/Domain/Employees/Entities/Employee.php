<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Employees\Entities;

class Employee
{
    public $number;
    public $firstname;
    public $lastname;
    public $title;
    public $department;
    public $username;

    public function __construct(?array $data=null)
    {
        if (!empty($data['number'    ])) { $this->number     = (int)$data['number']; }
        if (!empty($data['firstname' ])) { $this->firstname  = $data['firstname' ]; }
        if (!empty($data['lastname'  ])) { $this->lastname   = $data['lastname'  ]; }
        if (!empty($data['title'     ])) { $this->title      = $data['title'     ]; }
        if (!empty($data['department'])) { $this->department = $data['department']; }
        if (!empty($data['username'  ])) { $this->username   = $data['username'  ]; }
    }
}
