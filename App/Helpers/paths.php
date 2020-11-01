<?php namespace Ignite\Helpers;

if(!function_exists('file_exists_critical')) {
    function file_exists_critical($path) {
        if(file_exists($path))
            return true;
        else
            throw new \Exception("File: ". $path . " not found.");
    }
}

if(!function_exists('themes_data')) {
    function themes_data($name, $settings) {
        if(
            \file_exists(STORAGE_PATH . 'sys/theme-data/' . $name . '.json')
        ) {
            $data = \json_decode(\file_get_contents(STORAGE_PATH . 'sys/theme-data/' . $name . '.json'), true);
            $write = false;
            foreach($settings as $setting => $values) {
                if(!isset($data[$setting])) {
                    $write = true;
                    if(is_array($values) && isset($values['default'])) {
                        $data[$setting] = $values['default'];
                    } else
                        $data[$setting] = $values;
                }
            }

            if($write) {
                \file_put_contents(STORAGE_PATH . 'sys/theme-data/' . $name . '.json', \json_encode($data));
            }

            return $data;
        } else {
            $data = [];
            foreach($settings as $setting => $values) {
                if(is_object($values) && isset($values['default'])) {
                    $data[$setting] = $values['default'];
                } else
                    $data[$setting] = null;
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
            $data = \json_decode(\file_get_contents(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json'), true);
            $write = false;
            foreach($settings as $setting => $values) {
                if(!isset($data[$setting])) {
                    $write = true;
                    if(is_array($values) && isset($values['default'])) {
                        $data[$setting] = $values['default'];
                    } else
                        $data[$setting] = $values;
                }
            }

            if($write) {
                \file_put_contents(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json', \json_encode($data));
            }

            return $data;
        } else {
            $data = [];
            foreach($settings as $setting => $values) {
                if(is_array($values) && isset($values['default'])) {
                    $data[$setting] = $values['default'];
                } else
                    $data[$setting] = null;
            }

            \file_put_contents(STORAGE_PATH . 'sys/plugin-data/' . $name . '.json', \json_encode($data));

            return $data;
        }
    }
}

if(!function_exists('route_name')) {
    function route_name($name) {
        $name = trim($name, '/');
        $len = strlen($name);
        if($len)
            if($name[$len - 1] == '/')
                $name = substr($name, 0, $len - 1);

        //if($name != '')
            return '/' . $name;
        //else
            // return $name;
    }
}