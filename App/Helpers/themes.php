<?php namespace Ignition\Helpers;

if(!function_exists('theme_data')) {
    function theme_data($name, $settings) {
        if(
            \file_exists(STORAGE_PATH . 'sys/theme-data/' . $name . '.json')
        ) {
            $data = \json_decode(\file_get_contents(STORAGE_PATH . 'sys/theme-data/' . $name . '.json'));
            $write = false;
            foreach($settings as $setting => $values) {
                if(!isset($data->{$setting})) {
                    $write = true;
                    if(is_object($values) && isset($values->default)) {
                        $data->{$setting} = $values->default;
                    } else
                        $data->{$setting} = $values;
                }
            }

            if($write) {
                \file_put_contents(STORAGE_PATH . 'sys/theme-data/' . $name . '.json', \json_encode($data));
            }

            return $data;
        } else {
            $data = new \StdClass();
            foreach($settings as $setting => $values) {
                if(is_object($values) && isset($values->default)) {
                    $data->{$setting} = $values->default;
                } else
                    $data->{$setting} = null;
            }

            \file_put_contents(STORAGE_PATH . 'sys/theme-data/' . $name . '.json', \json_encode($data));

            return $data;
        }
    }
}

if(!function_exists('plugin_data')) {
    function plugin_data($name, $settings) {
        if(
            \file_exists(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json')
        ) {
            $data = \json_decode(\file_get_contents(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json'));
            $write = false;
            foreach($settings as $setting => $values) {
                if(!isset($data->{$setting})) {
                    $write = true;
                    if(is_object($values) && isset($values->default)) {
                        $data->{$setting} = $values->default;
                    } else
                        $data->{$setting} = $values;
                }
            }

            if($write) {
                \file_put_contents(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json', \json_encode($data));
            }

            return $data;
        } else {
            $data = new \StdClass();
            foreach($settings as $setting => $values) {
                if(is_object($values) && isset($values->default)) {
                    $data->{$setting} = $values->default;
                } else
                    $data->{$setting} = null;
            }

            \file_put_contents(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json', \json_encode($data));

            return $data;
        }
    }
}