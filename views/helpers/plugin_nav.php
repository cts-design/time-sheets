<?php
    class PluginNavHelper extends Helper {
        public $helpers = array('Html');

        public function links($type) {
            if (!$type) return false;

            $output = '';
            $config = Configure::read('plugins.'.$type);

            foreach ($config as $key => $value) {
                if (count($value['links']) === 1) {
                    $output .= '<li rel="plugin">';
                    $output .= $this->Html->link($value['links'][0]['title'], $value['links'][0]['link']);
                    $output .= '</li>';
                } else {
                    $output .= '<li rel="plugin">';
                    $output .= '<a>'. Inflector::humanize($key) .'</a>';
                    $output .= '<ul>';

                    foreach ($value['links'] as $link) {
                        $output .= '<li rel="plugin">';
                        $output .= $this->Html->link($link['title'], $link['link']);
                        $output .= '</li>';
                    }

                    $output .= '</ul></li>';
                }
            }

            return $output;
        }
    }
