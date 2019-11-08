<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * Search Results
 * @param array  $this->account_requests  Array of account_request entities
 * @param int    $this->total
 *
 * Search Parameters
 * @param string $this->id
 * @param string $this->requester_id
 * @param string $this->employee_number
 * @param string $this->type
 * @param string $this->status
 * @param int    $this->itemsPerPage
 * @param int    $this->currentPage
 *
 * Metadata
 * @param array $this->types
 * @param array $this->statuses
 */
declare (strict_types=1);
use Web\Block;
use Web\Paginator;
?>
<section>
    <header>
        <h1><?= $this->_(['account_request', 'account_requests', count($this->account_requests)]); ?></h1>
    </header>
    <form method="get">
        <fieldset>
            <?php
                $h = $this->template->getHelper('field');

                $options = [['value'=>'']];
                foreach ($this->statuses as $s) { $options[] = ['value'=>$s]; }
                echo $h->field([
                    'name'    => 'status',
                    'id'      => 'status',
                    'label'   => $this->_('status'),
                    'type'    => 'select',
                    'options' => $options,
                    'value'   => $this->status
                ]);

                $options = [['value'=>'']];
                foreach ($this->types as $s) { $options[] = ['value'=>$s]; }
                echo $h->field([
                    'name'    => 'type',
                    'id'      => 'type',
                    'label'   => $this->_('type'),
                    'type'    => 'select',
                    'options' => $options,
                    'value'   => $this->type
                ]);
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
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
