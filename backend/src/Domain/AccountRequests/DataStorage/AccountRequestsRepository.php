<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests\DataStorage;

use Domain\AccountRequests\Entities\AccountRequest;
use Domain\AccountRequests\UseCases\Search\Request as SearchRequest;

interface AccountRequestsRepository
{
    public function load(int $id): AccountRequest;
    public function find(SearchRequest $req): array;
}
