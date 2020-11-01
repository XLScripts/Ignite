<?php namespace Ignite\Services;

/*
* A Service Class to load  the currently selected user preferences for a Plugin / Theme
*/
class DataLoader {
    public static function Theme($meta) {
        if(
            isset($meta['theme'])
            && isset($meta['theme']['shortcode'])
        ) {
            if(!isset($meta['settings']) || !is_array($meta['settings']) || empty($meta['settings'])) {
                return [];
            } else {
                $sc = $meta['theme']['shortcode'];

                if(\file_exists(STORAGE_PATH . 'sys/theme-data/' . $sc . '.json')) {
                    $data = \json_decode(\file_get_contents(STORAGE_PATH . 'sys/theme-data/' . $sc . '.json'), true);
                    $write = false;
                    foreach($meta['settings'] as $setting => $values) {
                        if(!array_key_exists($setting, $data)) {
                            $write = true;
                            if(is_array($values) && isset($values['default'])) {
                                $data[$setting] = $values['default'];
                            } else
                                $data[$setting] = $values;
                        }
                    }
        
                    if($write) {
                        \file_put_contents(STORAGE_PATH . 'sys/theme-data/' . $sc . '.json', \json_encode($data));
                    }
        
                    return $data;
                } else {
                    $data = [];
                    foreach($meta['settings'] as $setting => $values) {
                        if(is_array($values) && isset($values['default'])) {
                            $data[$setting] = $values['default'];
                        } else
                            $data[$setting] = null;
                    }

                    \file_put_contents(STORAGE_PATH . 'sys/theme-data/' . $sc . '.json', \json_encode($data));

                    return $data;
                }
            }
        } else
            throw new \Exception('Invalid Meta file for the theme. Must include a "themes" with a "shortcode" property.');
    }
}