<?php
// include syntax tree and string administration
require('SyntaxTree.php');
require('StringData.php');

// some error handler
class ErrorHandler
{
    public function throwError($message, $line){
        $lineNumber = $line[0];
        $linePos    = $line[1];
        $message .= ' on line '.$lineNumber.', column '.$linePos.".\n";
        die($message);
    }
}

// parses file
class Parser extends ErrorHandler
{
    // SyntaxTree class
    private $_tree;
    // StringData class
    private $_s;



    // constructor
    public function __construct($string)
    {
        // load classes
        $this->_s    = new StringData($string);
        $this->_tree = new SyntaxTree;
        // start parsing
        $this->_rules();
    }
    
    // returns generated syntax tree
    public function getTree()
    {
        return $this->_tree->getTree();
    }

    // normal classes
    
    // RULES ::= RULE ";" RULES
    //       |   EOF
    private function _rules()
    {
        $this->_s->skipWhiteSpaces();
        // RULE ";" RULES
        if ($this->_s->charInSet('UC')){
            $this->_rule();
            // ";" RULES
            if ($this->_s->char === ';'){
                
                // new
                $this->_tree->nextRule();
               
                $this->_s->nextChar();
                // RULES
                $this->_rules();
            }
            else {
                $this->throwError(ERROR_EXP_SCOLON, $this->_s->getLine());
            }
        }
        // EOF
        elseif ($this->_s->char !== CHAR_EOF){
            $this->throwError(ERROR_EXP_EOF, $this->_s->getLine());
        }
        
    }
    
    // RULE ::= NONTERM ":" SUBRULES
    private function _rule()
    {
        // NONTERM ":" SUBRULES
        $nonterm = $this->_nonterm();
        $this->_tree->addSymbol(SYM_NONTERM, array($nonterm));
        $this->_s->skipWhiteSpaces();
        // ":" SUBRULES
        if ($this->_s->char === ':'){        
            $this->_s->nextChar();
            // SUBRULES
            $this->_subrules();
        }
        else {
            $this->throwError(ERROR_EXP_COLON, $this->_s->getLine());
        }
    }
    
    // SUBRULES ::= STATEMENTS "|" SUBRULES
    //          |
    private function _subrules()
    {
        if ($this->_s->char !== ';'){
            $this->_s->skipWhiteSpaces();
            // STATEMENTS "|" SUBRULES
            $this->_statements();
            $this->_s->skipWhiteSpaces();
            // "|" SUBRULES
            if ($this->_s->char === '|'){
                $this->_tree->nextBranch();
                $this->_s->nextChar();
                // SUBRULES
                $this->_subrules();
            }
        }
    }
    
    // STATEMENTS ::= STATEMENT STATEMENTS
    //            | \
    private function _statements()
    {
        // \
        if ($this->_s->char === CHAR_LAMBDA){
            $this->_tree->nextBranch();
            $this->_tree->addSymbol(SYM_EMPTY);
            $this->_s->nextChar();
        }
        else {
            // STATEMENT STATEMENTS
            if ($this->_s->charInSet('UC') || $this->_s->charInSet('LC')){
                $this->_statement();
                $this->_s->skipWhiteSpaces();
                // STATEMENTS
                $this->_statements();
            }
        }
    }
    
    // STATEMENT ::= NONTERM
    //           |   TERM
    // note: could be merged with _statements easily
    private function _statement()
    {
        // NONTERM
        if ($this->_s->charInSet('UC')){
            $this->_tree->addSymbol(SYM_NONTERM, array($this->_nonterm()));
        }
        // TERM
        elseif($this->_s->charInSet('LC')){
            $this->_term();
        }
        else {
            $this->throwError(ERROR_EXP_UCORLC, $this->_s->getLine());
        }
    }
    
    // NONTERM ::= UC
    private function _nonterm()
    {
        $name = $this->_s->getString('UC');
        return $name;
    }
    
    // TERM ::= LC SUBTERM
    // _and_
    // SUBTERM ::= "[" CONST "]"
    //         |   "{" VAR "}"
    private function _term()
    {
        // LC SUBTERM
        $type = $this->_s->getString('LC');
        // "[" CONST "]"
        if ($this->_s->char === '['){
            $this->_s->nextChar();
            // CONST "]"
            $this->_const($type);
            // "]"
            if ($this->_s->char === ']'){
                $this->_s->nextChar();
            }
            else {
                $this->throwError(ERROR_EXP_RSBRACK, $this->_s->getLine());
            }
        }
        // "{" VAR "}"
        elseif ($this->_s->char === '{'){
            $this->_s->nextChar();
            // VAR "}"
            $this->_var($type);
            // "}"
            if ($this->_s->char === '}'){
                $this->_s->nextChar();
            }
            else {
                $this->throwError(ERROR_EXP_RCBRACK, $this->_s->getLine());
            }
        }
        else {
            $this->throwError(ERROR_EXP_LSBRACKORLCBRACK, $this->_s->getLine());
        }
    }
    
    // CONST ::= AC
    //       |
    private function _const($type)
    {
        if ($this->_s->char !== "]"){
            $name = $this->_s->getString('AC');
            $this->_tree->addSymbol(SYM_CONST, array('normal', $type, $name));
        }
        else {
            $this->_tree->addSymbol(SYM_CONST, array('wildcard', $type));
        }
    } 
    
    // VAR ::= "+"
    //     |   "-"
    //     |   "++" LC
    //     |   "--" LC
    //     |   LC
    private function _var($type)
    {
        // "+"
        if ($this->_s->char === '+'){
            $this->_s->nextChar();
            // "++" LC
            if ($this->_s->char === '+'){
                $this->_s->nextChar();
                // LC
                $name = $this->_s->getString('LC');
                $this->_tree->addSymbol(SYM_VAR, array('++', $type, $name));
            }
            else {
                $this->_tree->addSymbol(SYM_VAR, array('+', $type));
            }
        }
        // "-"
        elseif ($this->_s->char === '-'){
            $this->_s->nextChar();
            // "--" LC
            if ($this->_s->char === '-'){
                $this->_s->nextChar();
                // LC
                $name = $this->_s->getString('LC');
                $this->_tree->addSymbol(SYM_VAR, array('--', $type, $name));
            }
            else {
                $this->_tree->addSymbol(SYM_VAR, array('-', $type));
            }
        }
        // LC
        else {
            $name = $this->_s->getString('LC');
            $this->_tree->addSymbol(SYM_VAR, array('normal', $type, $name));
        }
    }
}
?>