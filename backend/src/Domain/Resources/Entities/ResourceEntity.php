<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\Entities;
use Domain\Resources\ResourceService;

class ResourceEntity
{
    public $id;
    public $code;
    public $name;
    public $type;
    public $class;
    public $api_key;
    public $api_secret;
    public $fields;

    public function __construct(array $data)
    {
        if (!empty($data['id'        ])) { $this->id =    (int)$data['id'        ]; }
        if (!empty($data['code'      ])) { $this->code       = $data['code'      ]; }
        if (!empty($data['name'      ])) { $this->name       = $data['name'      ]; }
        if (!empty($data['type'      ])) { $this->type       = $data['type'      ]; }
        if (!empty($data['class'     ])) { $this->class      = $data['class'     ]; }
        if (!empty($data['api_key'   ])) { $this->api_key    = $data['api_key'   ]; }
        if (!empty($data['api_secret'])) { $this->api_secret = $data['api_secret']; }
        if (!empty($data['fields'    ])) {
            $this->fields = is_array(       $data['fields'])
                              ?             $data['fields']
                              : json_decode($data['fields'], true);
        }
    }

    /**
     * @param string $username  The username of the person doing the request
     */
    public function ServiceFactory(string $username): ResourceService
    {
        $class = $this->class;
        switch ($this->type) {
            case 'directory':
                global $DIRECTORY_CONFIG;
                return new $class($DIRECTORY_CONFIG['Ldap']);
            break;

            default:
                return new $class($this->api_key, $this->api_secret, $username);
        }

    }
}
