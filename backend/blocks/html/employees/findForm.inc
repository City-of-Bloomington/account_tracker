<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->firstname
 * @param string $this->lastname
 * @param array  $this->employees      Array of employee search results
 * @param int    $this->total
 * @param int    $this->itemsPerPage
 * @param int    $this->currentPage
 */
declare (strict_types=1);
use Web\Block;
use Web\Paginator;
?>
<section>
    <h1><?= $this->_(['employee', 'employees', 10]); ?></h1>
    <form method="get">
        <fieldset><legend><?= $this->_('search'); ?></legend>
            <?php
                $h = $this->template->getHelper('field');
                foreach (['firstname', 'lastname'] as $f) {
                    echo $h->field([
                        'name'  => $f,
                        'id'    => $f,
                        'label' => $this->_($f),
                        'value' => $this->$f
                    ]);
                }
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->employees) {
            $this->_include('employees/list.inc');
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
