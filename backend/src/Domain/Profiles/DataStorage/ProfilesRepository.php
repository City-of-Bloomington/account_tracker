<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\Profiles\DataStorage;

use Domain\Profiles\Entities\Profile;
use Domain\Profiles\UseCases\Search\Request as SearchRequest;

interface ProfilesRepository
{
    public function load(int $id): Profile;
    public function find(): array;

    public function save(Profile $profile): int;
}
