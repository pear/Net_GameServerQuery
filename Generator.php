<?php
class Generator extends ErrorHandler
{

    // syntax tree
    private $_tree;
    // lookahead sets
    private $_lookahead;
    // follow sets
    private $_follow;

    // constructor
    public function __construct($tree)
    {
        $this->_tree      = $tree;
        $this->_lookahead = array();
        $this->_follow    = array();
    }
    
    // calculate follow sets for each rule
    private function _getFollow($tree)
    {
        // loop through rules
        foreach($this->_tree AS $ruleName => $subrules){
            // loop through subrules
            for ($i = 0, $x = count($subrules); $i != $x; $i++){
                // loop through symbols
                $symbols = $subrules[$i];
                for ($j = 0, $y = count($symbols); $j != $y; $j++){
                
                    // check for nonterminals
                    if ($symbols[$j][0] === SYM_NONTERM){
                    
                        // get nonterminal name
                        $nonterm = $symbols[$j][1];
                        
                        // check if next symbol exists
                        if (isset($symbols[$j+1])){
                            
                            $symbol = $symbols[$j+1];
                        
                            // process symbol according to type
                            switch ($symbol[0]){
                            
                                // variable, include possible first characters
                                case SYM_VAR:
                                    // [TODO] add characters according to variable type
                                    break;
                                    
                                // constant, include first character
                                case SYM_CONST:
                                    // [TODO] format character according to constant type
                                    $this->_addFollowChar($nonterm, $symbol[3]);
                                    break;
                                    
                                // nonterminal, include follow (add nonterminal name to array)
                                case SYM_NONTERM:
                                    $this->_addFollowNonterm($symbol[1], $nonterm);
                                    break;                                
                            }
                            
                        }
                        // last symbol in the line, include follow of parent
                        else {
                            $this->_addFollowNonterm($nonterm, $ruleName);
                        }
                    }
                }

            }
            
            // merge follows, has to be done recursively somehow
        }
    }
    
    // make reference to another nonterminal, so that when the current is updated,
    // the added is also updated
    private function _addFollowNonterm($current, $toAdd)
    {
        $this->_follow[$current]['nonterm'][$toAdd] = true;
    }
    
    // recursively add character to follow set
    private function _addFollowChar($current, $char, $set = array())
    {
        $this->_follow[$current]['char'][$char] = true;
        $set[] = $current;
        
        // update all linked follows
        if (isset($this->_follow[$current]['nonterm'])){
            foreach($this->_follow[$current]['nonterm'] AS $nonterm => $value){
                // make sure no infinite loops occur
                if (!in_array($nonterm, $set)){
                    $this->_addFollowChar($current, $char, $set);
                }
            }
        }
    }

}
?>