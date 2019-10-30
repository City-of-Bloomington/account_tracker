<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Domain\AccountRequests;

class Metadata
{
    public static $types    = ['activate'];
    public static $statuses = ['pending', 'approved', 'completed'];
}