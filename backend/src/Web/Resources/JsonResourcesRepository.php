<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Resources;
use Domain\Resources\DataStorage\ResourcesRepository;

class JsonResourcesRepository implements ResourcesRepository
{
    /**
     * Look up resources using exact matching of fields
     */
    public function find(): array
    {

        $data = json_decode(file_get_contents(SITE_HOME.'/resources/resources.json'), true);
        if (!$data) { throw new \Exception(json_last_error_msg); }

        return $data;
    }
}
