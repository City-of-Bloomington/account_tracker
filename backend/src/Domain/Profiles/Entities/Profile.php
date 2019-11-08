<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\Entities;

class Profile
{
    public $id;
    public $code;
    public $name;
    public $questions;
    public $resources;

    public function __construct(array $data)
    {
        if (!empty($data['id'  ])) { $this->id   = (int)$data['id'  ]; }
        if (!empty($data['code'])) { $this->code =      $data['code']; }
        if (!empty($data['name'])) { $this->name =      $data['name']; }
        if (!empty($data['questions'])) {
            $this->questions = is_array($data['questions'])
                             ? $data['questions']
                             : json_decode($data['questions'], true);
        }
        if (!empty($data['resources'])) {
            $this->resources = is_array($data['resources'])
                             ? $data['resources']
                             : json_decode($data['resources'], true);
        }
    }
}
