<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\UseCases\Update;

class Request
{
    public $id;
    public $name;
    public $questions;
    public $resources;

    public function __construct(?array $data=null)
    {
        if (!empty($data['id'       ])) { $this->id   = (int)$data['id'  ]; }
        if (!empty($data['name'     ])) { $this->name =      $data['name']; }
        if (!empty($data['questions'])) { $this->questions = $data['questions']; }
        if (!empty($data['resources'])) { $this->resources = $data['resources']; }
    }
}
