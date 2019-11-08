<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\UseCases\Info;
use Domain\Profiles\Entities\Profile;

class Response
{
    public $profile;
    public $errors = [];

    public function __construct(?Profile $profile=null, ?array $errors=null)
    {
        $this->profile = $profile;
        $this->errors  = $errors;
    }
}
