<?php
/**
 * @copyright 2006-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * Form field values
 * @param Request $this->request
 *
 * Metadata
 * @param string $this->title
 * @param array  $this->roles
 * @param array  $this->authentication_methods
 */
$out = [
    'user' => $this->request
];
if (isset($_SESSION['errorMessages'])) {
    $out['errors'] = $_SESSION['errorMessages'];
}

echo json_encode($out, JSON_PRETTY_PRINT);
