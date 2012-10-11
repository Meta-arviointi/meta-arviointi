<?php

class CoffeeHelper extends AppHelper {

    public $helpers = array('Html');

    public function import($filename) {
        
        // Import and initialize Coffee compiler
        App::import('Vendor', 'CoffeeScript', array('file' => 'CoffeeScript' . DS . 'Init.php'));
        CoffeeScript\Init::load();

        // Paths
        $input_file = WWW_ROOT.DS.'coffee'.DS.$filename.'.coffee';
        $output_file = WWW_ROOT.DS.'js'.DS.$filename.'.js';

        // If modified, re-compile
        if(!file_exists($output_file) || filemtime($input_file) > filemtime($output_file)) {
            $input = file_get_contents($input_file);
            $output = "/* Generated by CoffeeHelper at " . date("Y-m-d H:i:s") . " */\n\n";
            $output .= CoffeeScript\Compiler::compile($input);
            file_put_contents($output_file, $output);
        }

        // Return ready-to-use script tag
        return $this->Html->script($filename);
    }  
}

?>