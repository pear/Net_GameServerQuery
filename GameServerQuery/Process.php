<?php
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Tom Buskens <ortega@php.net>                                |
// |          Aidan Lister <aidan@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id$


/**
 * Net_GameServerQuery_Process
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Process
{

    
    /**
     * Input string
     *
     * @var        string
     * @access     private
     */
    private $_string;


    /**
     * Encapsulation class
     *
     * @var        resource
     * @access     private
     */
    private $_Encap;


    /**
     * Parses input according to rules
     *
     * @access     public
     * @param      string    $string   Input string
     * @param      array     $rules    Processing rules
     * @return     array     Processed string
     */
    public function process($string, $rules)
    {
        // Initialize variables
        $this->_string = $string;
        $this->_Encap  = new Net_GameServerQuery_Process_Encapsulate;

        // Process input
        $this->_processString($rules);

        // Return processed input
        return $this->_Encap->getOutput();
    }
    

    /**
     * Recursively traverse rule array, applying each rule
     *
     * @access     private
     * @param      array     $rules    Array containing processing rules
     * @param      int       $depth    Current array depth
     */
    private function _processString($rules, $depth = 0)
    {
        // Initialize variables
        $rule_count = count($rules);
        $loop_count = 0;

        // Traverse tree
        while (true) {
            for ($i = 0; $i != $rule_count; $i++) {

                $rule = $rules[$i];

                // Entry is an array, process it
                if (is_array($rule)) {
                    $this->_processString($rule, $depth + 1);
                }
                
                // Entry is a rule, apply it
                elseif (isset($rule)) {
                    if ($this->_applyRule($rule) === false)
                    {
                        // At the beginning of a loop
                        if ($i === 0 && ($depth !== 0 || $loop_count !== 0))
                        {
                            return true;
                        } 
                        // No complete loop executed
                        else {
                            // TODO: error
                        }
                    }
                }
                
                // Entry not set
                else {
                    // TODO: error
                }
                
            }
            $loop_count++;
        }
    }

    
    /**
     * Applies a processing rule to input string
     *
     * @access     private
     * @param      string    $rule     Processing rule
     */
    private function _applyRule($rule)
    {
        // Get processing info from rule
        $rule = $this->_getRule($rule);

        // Match pattern
        if (isset($rule['pattern'])) {
            if ($this->_matchPattern($rule['pattern']) === false) {
                return false;
            }
        }

        // Process result
        if (isset($rule['process'])) {
            $this->_processMatch($rule['process']);
        }
        
        return true;
    }


    /**
     * Parses rule to retrieve processing data
     *
     * @access     private
     * @param      string    $rule     Processing rule
     * @return     array     Processing data
     */
    private function _getRule($rule)
    {
        $parsed_rule = array();

        // Split rule
        $pattern = "/ *\| */";
        $pieces  = preg_split($pattern, $rule);

        // Encapsulate pattern
        $parsed_rule['pattern'] = $this->_encapsulate($pieces[0], 'pattern');

        // Encapsulate process
        if (isset($pieces[1])) {
            $parsed_rule['process'] = $this->_encapsulate($pieces[1], 'process');
        }

        return $parsed_rule;        
    }


    /**
     * Encapsulate pattern and process
     *
     * @access     private
     * @param      string    $string   String to encapsulate
     * @param      string    $type     Type of string
     * @return     string    Encapsulated string
     */
     private function _encapsulate($string, $type)
     {
         // Encapsulate string according to type
         switch ($type) {

             // Encapsulate variable and function names
             case 'process':
                $string = preg_replace('/\$(\w+)/', "\$this->_Encap->vars['\\1']", $string);
                $string = preg_replace('/([^ ]+)\(/', "\$this->_Encap->\\1(", $string);
             break;
                 
             // Overwrite variable names to their values, add delimiters to expression
             case 'pattern':
                 $string = '/^'.preg_replace('/\$(\w+)/e', "\$this->_Encap->vars['\\1']", $string).'/';
                 break;
         }

         return $string;
     }


    /**
     * Matches input with pattern
     *
     * @access     private
     * @param      string    $pattern  Pattern to match input against
     */
    private function _matchPattern($pattern)
    {
        // Match pattern
        if (preg_match($pattern, $this->_string, $match)) {

            // Set match, remove match from input string
            $match = $match[0];   
            $this->_Encap->setMatch($match);
            $this->_string = substr($this->_string, strlen($match));
            
            return true;
        }
        // No match
        else {
            return false;
        }
    }

    
    /**
     * Processes last match
     *
     * @access     private
     * @param      string    $code     Code to process last match
     */
    private function _processMatch($code)
    {
        // eval'd code only uses variables and methods from $this->_Encap
        eval($code);
    }
    
}

/**
 * Net_GameServerQuery_Process_Encapsulate
 *
 * @version        0.1
 * @package        Net_GameServerQuery
 */
class Net_GameServerQuery_Process_Encapsulate
{
    /**
     * Holds all variables
     *
     * @var        array
     * @access     public
     */
    public $vars;


    /**
     * Holds formatted data
     *
     * @var        array
     * @access     private
     */
    private $_output;

    
    /**
     * Constructor
     *
     * @access     public
     */
    public function __construct()
    {
        // Initialize variables
        $this->vars    = array();
        $this->_output = array();
    }


    /**
     * Sets last match
     *
     * @access     public
     * @param      string    $value    Last match
     */
    public function setMatch($value)
    {
        $this->vars[1] = $value;
    }
    

    /**
     * Adds variable to output
     *
     * @access     public
     * @param      string    $name     Variable name
     */ 
    public function addVar($name)
    {
        // Existing variable
        if (isset($this->_output[$name])) {
            
            // Variable has one value, put it into an array
            if (!is_array($this->_output[$name])) {
                $this->_output[$name] = array($this->_output[$name]);
            }
            
            // Add current match to array
            array_push($this->_output[$name], $this->vars['1']);
            
        }
        // Fresh variable
        else {
            $this->_output[$name] = $this->vars['1'];
        }
    }

    
    /**
     * Returns formatted data
     *
     * @access     public
     * @return     array     Formatted data
     */
    public function getOutput()
    {
        return $this->_output;
    }

    
    /**
     * Converts last match to its byte value
     *
     * @access     public
     */
    public function toByte()
    {
        $this->vars[1] = ord($this->vars[1]);
    }
   
}



/**
 * Testing the stuff, gamespy style
 */
$rules  = array
(
    '\xFE\xFD\\x00',
    '.{4}           | addVar(\'string_header\');',
    '\\x00\xFF\xFF',
    '.              | toByte(); $header_count = $1; addVar(\'header_count\'); $i = 0;',
    array
    (
        '[^\\x00]+  | $headers[] = $1;',
        '\\x00'
    ),
    '\\x00          | $i = 0;',
    array
    (
        '[^\\x00]+  | addVar($headers[$i++]); $i = $i % $header_count;',
        '\\x00'
    ),
    '\\x00'
);

$string = "\xFE\xFD\x00BLAH\x00\xff\xff\x02head1\x00head2\x00\x00one\x00two\x00three\x00four\x00five\x00\x00";

$process = new Net_GameServerQuery_Process;
print_r($process->process($string, $rules));


/**
 * Ut2k3 style
 */
$rules = array
(
    '.           | toByte(); $length = $1;',
    '.{$length}  | $name   = $1;',
    '.           | toByte(); $length = $1;',
    '.{$length}  | addVar($name);'
);

$string = "\x03one\x03two\x05three\x04four\x04five\x03six";
print_r($process->process($string, $rules));

?>
