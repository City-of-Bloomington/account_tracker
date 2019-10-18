<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->account_requests  Array of account_request entities
 * @param int    $this->total
 * @param int    $this->itemsPerPage
 * @param int    $this->currentPage
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_(['account_request', 'account_requests', count($this->account_requests)]); ?></h1>
    </header>
    <?php
        if ($this->account_requests) {
            $this->_include('account_requests/list.inc');
        }

        if ($this->total > $this->itemsPerPage) {
            $block = new Block('pageNavigation.inc', [
                'paginator' => new Paginator(
                    $this->total,
                    $this->itemsPerPage,
                    $this->currentPage
            )]);
            echo $block->render('html', $this->template);
        }
    ?>
</section>
