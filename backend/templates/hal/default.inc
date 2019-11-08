<?php
header('Content-type: application/hal+json; charset=utf-8');
echo $this->includeBlocks();

if (isset($_SESSION['errorMessages'])) {
    unset($_SESSION['errorMessages']);
}
