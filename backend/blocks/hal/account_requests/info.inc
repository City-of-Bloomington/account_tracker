<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param AccountRequest $account_request
 */
declare (strict_types=1);

$links = [
    'self' => ['href'=>parent::generateUri('account_requests.view', ['id'=>$this->account_request->id])]
];
if (parent::isAllowed('account_requests', 'update')) {
    $links['edit'] = ['href' => parent::generateUri('account_requests.update', ['id'=>$this->account_request->id])];
}

$this->account_request->_links = $links;

echo json_encode($this->account_request, JSON_PRETTY_PRINT);
