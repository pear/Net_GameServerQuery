<?php
// special characters
define('CHAR_EOF',    "\x00");
define('CHAR_LAMBDA', '\\');           

// symbols
define('SYM_NONTERM', 'nonterm');
define('SYM_COLON',   'colon');
define('SYM_SCOLON',  'semicolon');
define('SYM_VBAR',    'verticalbar');
define('SYM_CONST',   'constant');
define('SYM_VAR',     'variable');
define('SYM_EOF',     'eof');
define('SYM_EMPTY',   'empty');

// errors
define('ERROR_EXP_COLON',            '":" expected');
define('ERROR_EXP_SCOLON',           '";" expected');
define('ERROR_EXP_RSBRACK',          '"]" expected');
define('ERROR_EXP_RCBRACK',          '"}" expected');
define('ERROR_EXP_LSBRACKORLCBRACK', '"[" or "{" expected');
define('ERROR_EXP_CHARSET',          'unexpected character');
define('ERROR_EXP_UCORLC',           'lowercase or uppercase character expected');
define('ERROR_EXP_EOF',              'end of file or uppercase character expected');

// includes
require_once('Parser.php');
require_once('Generator.php');

class ParserGenerator
{
    public function generate($string)
    {
        $tree = new Parser($string);
        return $tree->getTree();
        //return new Generator($tree);
    }
}

$string = 
'RECEIVE      : SEND byte[00] bla[04] byte[05] byte[06] byte[07] INFO PLAYERS TEAM 
;

INFO         : nulstr{+} nulstr{-} INFO
             | byte[00]
             ;

PLAYERS      : byte{playercount} PLAYERHEADER PLAYER byte[00] ;

PLAYERHEADER : nulstr{++player} PLAYERHEADER
             | byte[00]
             ;

PLAYER       : nulstr{--player} PLAYER
             | \\
             ;

TEAMS        : byte{teamcount} TEAMHEADER TEAM byte[00] ;

TEAMHEADER   : nulstr{++team} TEAMHEADER
             | byte[00]
             ;

TEAM         : nulstr{--team} TEAM
             | byte[00]
             ;
';

$string2 = '
BLA : BLA;
'
;

$pg = new ParserGenerator;
print_r($pg->generate($string));
print_r($pg->generate($string2));
?>
