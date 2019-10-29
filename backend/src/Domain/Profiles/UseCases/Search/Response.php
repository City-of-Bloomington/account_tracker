<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\UseCases\Search;

class Response
{
    public $profiles = [];
    public $errors   = [];

    public function __construct(?array $profiles=null, ?array $errors=null)
    {
        $this->profiles = $profiles;
        $this->errors   = $errors;
    }
}
