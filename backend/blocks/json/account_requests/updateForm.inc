<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param UpdateRequest  $this->request
 * @param UpdateResponse $this->response
 */
declare (strict_types=1);

if ($this->response && $this->response->errors) {
    $this->request->errors = $this->response->errors;
}
echo json_encode($this->request, JSON_PRETTY_PRINT);
