<?php
// class which builds the syntax tree
class SyntaxTree extends ErrorHandler
{
    // the syntax tree
    private $_tree = array();
    // current production rule
    private $_rule = 0;
    // current branch
    private $_branch = 0;

    // returns syntax tree
    public function getTree()
    {
        return $this->_tree;
    }
    
    // add a symbol to current syntax tree
    public function addSymbol($name, $params = false)
    {
        switch($name)
        {
            // lambda symbol, empty line
            case SYM_EMPTY:
                $this->_tree[$this->_rule][$this->_branch][][0] = $name;
                break;
            // nonterminal
            case SYM_NONTERM:
                // check if it's a header, if not, treat it like any other symbol
                if (!isset($this->_tree[$this->_rule])){
                    $this->_rule = $params[0];
                    $this->_tree[$this->_rule] = array();
                    break;
                }
            // terminals & nonterminals
            default:
                $symbolData[] = $name;
                for ($i = 0, $x = count($params); $i != $x; $i++){
                    $symbolData[] = $params[$i];
                }
                
                $this->_tree[$this->_rule][$this->_branch][] = $symbolData;
                
                break;
        }
    }
    
    // set next rule
    public function nextRule()
    {
        $this->_rule++;
        $this->_branch = 0;
    }
    
    // set next branch
    public function nextBranch()
    {
        $this->_branch++;
    }
}
?>