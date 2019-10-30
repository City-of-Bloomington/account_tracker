<?php
header('Content-type: application/json; charset=utf-8');
echo $this->includeBlocks();

if (isset($_SESSION['errorMessages'])) {
    unset($_SESSION['errorMessages']));
}
