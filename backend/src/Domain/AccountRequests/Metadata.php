<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests;

class Metadata
{
    const TYPE_ACTIVATE    = 'activate';
    const STATUS_PENDING   = 'pending';
    const STATUS_COMPLETED = 'completed';

    public static $types    = [self::TYPE_ACTIVATE];
    public static $statuses = [self::STATUS_PENDING, self::STATUS_COMPLETED];
}
