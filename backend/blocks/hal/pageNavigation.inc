<?php
/**
 * Displays page navigation for any list that has pagination turned on
 *
 * @copyright 2007-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Paginator $this->paginator
 */
use Web\Url;
$pages = $this->paginator->getPages();
$links = [];
if (count($pages->pagesInRange) > 1) {

	$url = new Url(Url::current_url(BASE_HOST));
	$url->purgeEmptyParameters();

	// Show the Back button
	if (isset($pages->previous)) {
		$url->page = $pages->first;
		$links['first'] = ['href'=>"$url"];

		$url->page = $pages->previous;
		$links['previous'] = ['href'=>"$url"];
	}

	// Show the Next button
	if (isset($pages->next)) {
		$url->page = $pages->next;
		$links['next'] = ['href'=>"$url"];

		$url->page = $pages->last;
		$links['last'] = ['href'=>"$url"];
	}
}
return $links;
