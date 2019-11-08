<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int      $this->employee_number
 * @param int      $this->profile_id
 * @param Employee $this->employee
 * @param array    $this->profiles  An array of Profile objects
 */
declare (strict_types=1);
$output = [
    'employee' => $this->employee,
    'profiles' => $this->profiles
];
if (!empty($_SESSION['errorMessages'])) {
    $output['errors'] = $_SESSION['errorMessages'];
}
echo json_encode($output, JSON_PRETTY_PRINT);
