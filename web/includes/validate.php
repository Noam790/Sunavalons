<?php
function validate_ville($ville) {
    $ville = urldecode($ville);
    return is_string($ville) && preg_match('/^[\p{L} _\-]+$/u', $ville);
}
