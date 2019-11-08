<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->account_requests
 * @param int   $this->total
 * @param int   $this->itemsPerPage
 * @param int   $this->currentPage
 */
declare (strict_types=1);
$out = [
    'account_requests' => $this->account_requests,
    'total'            => $this->total,
    'itemsPerPage'     => $this->itemsPerPage,
    'currentPage'      => $this->currentPage
];
echo json_encode($out, JSON_PRETTY_PRINT);
