<?php
function validate_ville($ville) {
    return is_string($ville) && preg_match('/^[a-zA-Z_\- ]+$/u', $ville);
}
