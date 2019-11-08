<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->users
 */
declare (strict_types=1);
$out = [
    'users' => $this->users
];
if (isset($_SESSION['errorMessages'])) {
    $out['errors'] = $_SESSION['errorMessages'];
}
echo json_encode($out, JSON_PRETTY_PRINT);
