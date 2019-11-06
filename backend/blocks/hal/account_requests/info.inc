<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param AccountRequest $this->account_request
 * @param array          $this->actions         // Array of valid action links
 */
declare (strict_types=1);

$this->account_request->_links = $this->actions;

echo json_encode($this->account_request, JSON_PRETTY_PRINT);
