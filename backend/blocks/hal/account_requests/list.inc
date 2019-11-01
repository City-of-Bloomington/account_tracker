<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->account_requests
 * @param int   $this->total
 * @param int   $this->itemsPerPage
 * @param int   $this->currentPage
 * @param User  $this->user
 */
declare (strict_types=1);
use Web\Paginator;
use Web\Url;

$out = [
    '_links' => [
        'self' => ['href' => parent::generateUri('account_requests.index')]
    ],
    '_embedded' => [
        'account_requests' => []
    ],
    'total'            => $this->total,
    'itemsPerPage'     => $this->itemsPerPage,
    'currentPage'      => $this->currentPage
];

$userCanView   = parent::isAllowed('account_requests', 'view');
$userCanEdit   = parent::isAllowed('account_requests', 'update');
$userCanDelete = parent::isAllowed('account_requests', 'delete');
foreach ($this->account_requests as $a) {
    $links = [];
    if ($userCanView) {
        $links['self'  ] = ['href' => parent::generateUri('account_requests.view',   ['id'=>$a->id])];
    }
    if ($userCanEdit) {
        $links['edit'  ] = ['href' => parent::generateUri('account_requests.update', ['id'=>$a->id])];
    }
    if ($userCanDelete) {
        $links['delete'] = ['href' => parent::generateUri('account_requests.delete', ['id'=>$a->id])];
    }
    if ($links) {
        $a->_links = $links;
    }

    $out['_embedded']['account_requests'][] = $a;
}

if ($this->total > $this->itemsPerPage) {
    $out['_links'] = array_merge($out['_links'], getPageLinks(new Paginator($this->total, $this->itemsPerPage, $this->currentPage)));
}

echo json_encode($out, JSON_PRETTY_PRINT);

function getPageLinks(Paginator $paginator): array
{
    $pages = $paginator->getPages();
    $links = [];
    if (count($pages->pagesInRange) > 1) {

        $url = new Url(Url::current_url(BASE_HOST));
        $url->purgeEmptyParameters();

        // Show the Back button
        if (isset($pages->previous)) {
            $url->page = $pages->first;
            $links['first'] = ['href'=>$url->getUri()];

            $url->page = $pages->previous;
            $links['previous'] = ['href'=>$url->getUri()];
        }

        // Show the Next button
        if (isset($pages->next)) {
            $url->page = $pages->next;
            $links['next'] = ['href'=>$url->getUri()];

            $url->page = $pages->last;
            $links['last'] = ['href'=>$url->getUri()];
        }
    }
    return $links;
}
