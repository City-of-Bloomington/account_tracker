<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\Entities;

class ResourceEntity
{
    public $code;
    public $name;
    public $type;
    public $definition;

    public function __construct(array $data)
    {
        if (!empty($data['code'      ])) { $this->code = $data['code']; }
        if (!empty($data['name'      ])) { $this->name = $data['name']; }
        if (!empty($data['type'      ])) { $this->type = $data['type']; }
        if (!empty($data['definition'])) {
            $this->definition = is_array(   $data['definition'])
                              ?             $data['definition']
                              : json_decode($data['definition'], true);
        }
    }
}
