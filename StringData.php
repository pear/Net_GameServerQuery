<?php
// manages string data
class StringData extends ErrorHandler
{
    // parse string
    private $_string;
    // character position
    private $_pos     = 0;
    // current line number
    private $_line    = 1;
    // current position on line
    private $_linePos = 1;
    // current character
    public $char;
    // character sets
    private $_charSets;



    // constructor
    function __construct($string)
    {
        // set string, first character
        $this->_string  = $string.CHAR_EOF;
        $this->char     = $this->_string{0};

        // load character sets
        require('CharSets.php');
        $this->_charSets = $charSets;
    }

    // get next character
    public function nextChar()
    {
        $this->char = $this->_string{++$this->_pos};
        $this->_linePos++;
    }

    // skip whitespaces
    public function skipWhiteSpaces()
    {
        // check if current character is a whitespace
        if (strpos($this->_charSets['WS'][0], $this->char) !== false){

            // skip all following whitespaces, untill a non-whitespace char is encountered
            do {
                // linebreak, increment linecount, reset line position
                if ($this->char === "\n"){
                    $this->_line++;
                    $this->_linePos = 1;
                }
                $this->nextChar();
                $charPos = strpos($this->_charSets['WS'][0], $this->char);
            }
            while ($charPos !== false);
        }
    }
    
    // check if the current character is contained in a set
    public function charInSet($set)
    {
        // get charset
        $charSet = $this->_charSets[$set][0];
        
        if (strpos($charSet, $this->char) === false){
            return false;
        }
        else {
            return true;
        }        
    }

    // get a character string
    public function getString($set)
    {
        // get charset
        $charSet = $this->_charSets[$set];

        // throw error if the first character is not in the set
        if (strpos($charSet[0], $this->char) === false){
            $this->_s->throwError(ERROR_EXP_CHARSET, array($this->_line, $this->_linePos));
        }
        // get string
        else {
            // set first char
            $string = $this->char;
            $this->nextChar();
            // add characters as long as they're in the set
            while (strpos($charSet[1], $this->char) !== false){
                $string .= $this->char;
                $this->nextChar();
            }
        }

        return $string;
    }
    
    // returns current character line number and position
    public function getLine()
    {
        return array($this->_line, $this->_linePos);
    }
}
?>