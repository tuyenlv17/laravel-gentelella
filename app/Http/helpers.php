<?php

/**
 * set active menu
 * @param type $route
 * @return type
 */
function setActiveMenu($route) {
    if (is_array($route)) {
        foreach ($route as $r) {
            if (Request::is($r)) {
                return 'active open';
            }
        }

        return '';
    }
    return Request::path() == $route ? 'active open' : '';
}

/**
 * set open parent menu
 * @param type $route
 */
function setOpenMenu($route) {
    if (is_array($route)) {
        foreach ($route as $r) {
            if (Request::is($r)) {
                return 'open';
            }
        }

        return '';
    }
    return Request::path() == $route ? 'open' : '';
}

?>
