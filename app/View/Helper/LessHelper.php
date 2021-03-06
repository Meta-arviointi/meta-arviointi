<?php

class LessHelper extends AppHelper {

    public $helpers = array('Html');

    public function import($filename) {
        
        // Paths
        $input_file = WWW_ROOT.'less'.DS.$filename.'.less';
        $output_file = WWW_ROOT.'css'.DS.$filename.'.css';

        // If modified, re-compile
        if(!file_exists($output_file) || filemtime($input_file) > filemtime($output_file)) {
            $input = file_get_contents($input_file);
            $output = "/* Generated by LessHelper at " . date("Y-m-d H:i:s") . " */\n\n";
            $output .= $this->compile($input);
            file_put_contents($output_file, $output);
        }

        // Return ready-to-use css tag
        return $this->Html->css($filename);
    }

    public function compile($input) {
        // Import and initialize less compiler
        App::import('Vendor', 'LessPHP', array('file' => 'lessphp' . DS . 'lessc.inc.php'));
        $less = new lessc;

        return $less->compile($input);
    }
}

?>