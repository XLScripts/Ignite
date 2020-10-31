<?php namespace Ignite\Helpers;

function base_path($str = '') {
    return realpath(BASE_PATH . $str);
};