<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\Update;
use Domain\Resources\Entities\ResourceEntity;

class Request
{
    public $id;
    public $code;
    public $name;
    public $type;
    public $class;
    public $order;
    public $api_key;
    public $api_secret;

    public function __construct(?array $data=null)
    {
        if (!empty($data['id'        ])) { $this->id =    (int)$data['id'        ]; }
        if (!empty($data['code'      ])) { $this->code       = $data['code'      ]; }
        if (!empty($data['name'      ])) { $this->name       = $data['name'      ]; }
        if (!empty($data['type'      ])) { $this->type       = $data['type'      ]; }
        if (!empty($data['class'     ])) { $this->class      = $data['class'     ]; }
        if (!empty($data['order'     ])) { $this->order = (int)$data['order'     ]; }
        if (!empty($data['api_key'   ])) { $this->api_key    = $data['api_key'   ]; }
        if (!empty($data['api_secret'])) { $this->api_secret = $data['api_secret']; }
    }
}
