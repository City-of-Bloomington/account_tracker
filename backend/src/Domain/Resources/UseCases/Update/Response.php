<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Resources\UseCases\Update;

class Response
{
    public $code;
    public $errors = [];

    public function __construct(?string $code=null, ?array $errors=null)
    {
        $this->code   = $code;
        $this->errors = $errors;
    }
}
