<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2 of the PHP license,         |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://au.php.net/license/3_0.txt.                                   |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Tom Buskens <ortega@php.net>                                 |
// +----------------------------------------------------------------------+
//
// $id$
// $revision$


/* Error messages */
define('NET_GAMESERVERQUERY_PARSESTRING_UNEXPPATTERN',  'unexpected pattern');
define('NET_GAMESERVERQUERY_PARSESTRING_RULENOTSET',    'rule not set'); 
define('NET_GAMESERVERQUERY_PARSESTRING_INVDATATYPE',   'invalid data type');
define('NET_GAMESERVERQUERY_PARSESTRING_INVTYPELENGTH', 'invalid type length');
define('NET_GAMESERVERQUERY_PARSESTRING_NOTEMP',        'temp value not found');


/**
 * Net_GameServerQuery_ParseString
 *
 * @version 0.0
 * @package Net_GameServerQuery
 */
class Net_GameServerQuery_ParseString
{

    /**
     * Server response
     *
     * @var          string
     * @access       private
     */ 
    private $_string;

    
    /**
     * Last matched string
     *
     * @var          string
     * @access       private
     */
    private $_currentMatch;

    
    /**
     * Rules to apply to current string
     *
     * @var          array
     * @acess        private
     */
    private $_currentRules;

    
    /**
     * Formatted data
     *
     * @var          mixed array
     * @access       private
     */
    private $_output;

    /**
     * Temporary data
     *
     * @var          mixed array
     * @access       private
     */
    private $_temp;


    /** 
     * Prints out error, should be changed
     *
     * @access       private
     * @param        string $errmsg The error message
     * @param        int $errno The error number
     */
    private function _throwError($errmsg, $errno)
    {
        $message = '<p>Fatal error ('.$errno.'): '.$errmsg."</p>\n";
        print_r($this->_output);
        die($message);
    }
     
    /**
     * Matches and processes input according to pattern array
     *
     * @access       public
     * @param        string $string The server response
     * @param        array  $rules The array containing processing information
     * @return       array  The parsed string data
     */      
    public function parse($string, $rules)
    {
        /* Initialize variables */
        $this->_string = $string;
        $this->_output = array();
        $this->_temp   = array();

        /* Parse string */
        $this->_parseString($rules);

        /* Return formatted data */
        return $this->_output;
    }

    
    /**
     * Recursively walks through array, processing the server response
     *
     * @access       private
     * @param        array  $rules The array containing processing information
     * @param        int $currentDepth Current depth of the array
     */ 
    private function _parseString($rules, $currentDepth = 0)
    {
        /* Parse string while pattern matches */
        $x = count($rules);
        $loopcount = 0;
        while (true) {
            for ($i = 0; $i != $x; $i++) {
                /* Rule is an array, recursively apply array */
                if (is_array($rules[$i])) {
                    $this->_parseString($rules[$i], $currentDepth + 1);
                }
                /* Normal rule, apply it */
                elseif (isset($rules[$i])) {
                    if (!$this->_applyRule($rules[$i])) {
                        /* Don't throw error when the loop is at the beginning, 
                         * except when on base level with 0 loops executed (i.e
                         * empty string
                         */
                        if ($i === 0 && !($currentDepth === 0 && $loopcount === 0)) {
                            return true;
                        }
                        /* Throw error */
                        else {
                            /* Could not apply rule, throw error */
                            $this->_throwError(NET_GAMESERVERQUERY_PARSESTRING_UNEXPPATTERN, 0); 
                        }
                    }
                }
                /* Rule not set */
                else {
                    $this->_throwError(NET_GAMESERVERQUERY_PARSESTRING_RULENOTSET, 1);
                }
            }
            ++$loopcount;
        }
    } 


    /**
     * Applies rule to string
     *
     * @access       private
     * @param        string $rule String containing processing information
     */ 
    private function _applyRule($rule)
    {
        /* Get parsing info from rule */
        $this->_getRule($rule);
        
        /* Match string */
        if (!$this->_matchString()) {
            return false;
        }
        else {

            /* Convert match to proper type */
            $this->_matchToType();

            /* Store value in output array */
            $this->_storeMatch();

            /* Store value in temp array */
            $this->_storeMatchTemp();            
            
            return true;
        }
    }

    
    /**
     * Retrieves data from rule string
     *
     * @access       private
     * @param        string $rule String containing processing information
     */ 
    private function _getRule($ruleString)
    {
        /* Split string, get pieces */
        $pieces = explode('|', $ruleString);
        switch(count($pieces)) {

            case 4:
                $rule['temp']    = $pieces[3];
              
            case 3:
                $rule['type']    = $pieces[1];
                $rule['name']    = $pieces[2];

            case 1:
                $rule['pattern'] = $pieces[0];
                break;

            /* Invalid format, throw error */
            default:
                $this->_throwError(NET_GAMESERVERQUERY_PARSESTRING_INVDATATYPE, 2);
                break;
        }

        $this->_currentRules = $rule;
    }


    /**
     * Matches server response with regular expression
     *
     * @access       private
     * @return       Bool True if string was matched, false otherwise
     */ 
    private function _matchString()
    {
        /* Format pattern */
        $pattern = '/^'.$this->_currentRules['pattern'].'/';

        /* Match pattern, remove match from string */
        if (preg_match($pattern, $this->_string, $match)) {
            $this->_currentMatch = $match[0];
            $this->_string       = substr($this->_string, strlen($this->_currentMatch));
            return true;
        }
        /* No match */
        else {
            return false;
        }
        
    }

    
    /**
     * Converts last match according to data type
     *
     * @access       private
     */ 
    private function _matchToType()
    {
        /* Convert current match according to type */
        if (isset($this->_currentRules['type'])) {

            $type = $this->_currentRules['type'];

            /* Check length */
            $length = array ( 
                'int32'   => 4, 
                'float32' => 4,
                'int16'   => 2,
                'byte'    => 1
            );
            if (isset($length[$type]) && strlen($this->_currentMatch) !== $length[$type]) {
                /* TODO: throw error */
                $this->_throwError(NET_GAMESERVERQUERY_PARSESTRING_INVTYPELENGTH, 3);
            }

            /* Format according to type */
            switch ($type) {

                case 'int16':
                    $unpack = unpack('Sshort', $this->_currentMatch);
                    $this->_currentMatch = $unpack['short'];
                    break;

                case 'int32':
                    /* TODO */
                    break;

                case 'float32':
                    /* TODO */
                    break;

                case 'byte':
                    $this->_currentMatch = ord($this->_currentMatch);
                    break;

                case 'string':
                    break;
                
                default:
                    /* TODO: Throw error */
            }
        }
    }

 
    /**
     * Stores current match in output array
     *
     * @access       private
     */ 
    private function _storeMatch()
    {
        /* Get variable name */
        if (!empty($this->_currentRules['name'])) {
            $name = $this->_currentRules['name'];
            if ($name{0} === '$') {
                $name = substr($name, 1);
                if (isset($this->_temp[$name])) {
                    $name = $this->_temp[$name];
                }
                /* Error */
                else {
                    $this->_throwError(NET_GAMESERVERQUERY_PARSESTRING_NOTEMP, 4);
                }
            }
         
        
            /* Old variable */
            if (isset($this->_output[$name])) {
            
                /* Variable has 1 value, put it into an array */
                if (!is_array($this->_output[$name])) {
                    $this->_output[$name] = array($this->_output[$name]);
                }
            
             /* Add current match to array */
                array_push($this->_output[$name], $this->_currentMatch);
            
            }
            /* Fresh variable */
            else {
                $this->_output[$name] = $this->_currentMatch;
            }
        }
        
    }

    
    /**
     * Stores current match in temporary array
     *
     * @access       private
     */ 
    private function _storeMatchTemp()
    {
        /* store current match in temp array, overwrite any existing data */
        if (isset($this->_currentRules['temp'])) {
            $varname = substr($this->_currentRules['temp'], 1);
            $this->_temp[$varname] = $this->_currentMatch;
        }
    }
}


/* Halflife rules example */
$rules = array(
    '\xff\xff\xff\xff',
    '.{2}|int16|rulecount',
    '\\x00',
    array(
        '[^\\x00]+|string||$varname',
        '\\x00',
        '[^\\x00]+|string|$varname',
        '\\x00'
    )
);

/* String: */
$string = 'ÿÿÿÿE] _tutor_bomb_viewable_check_interval 0.5 _tutor_debug_level 0 _tutor_examine_time 0.5 _tutor_hint_interval_time 10.0 _tutor_look_angle 10 _tutor_look_distance 200 _tutor_message_character_display_time_coefficient 0.07 _tutor_message_minimum_display_time 1 _tutor_message_repeats 5 _tutor_view_distance 1000 admin_highlander 0 admin_ignore_immunity 0 admin_mod_version 2.50.59 (MM) admin_quiet 0 allow_client_exec 1 allow_spectators 1.0 ami_sv_maxplayers 16 amv_private_server 0 coop 0 deathmatch 1 decalfrequency 30 default_access 0 edgefriction 2 hostage_debug 0 humans_join_team any max_queries_sec 1 max_queries_sec_global 1 max_queries_window 1 metamod_version 1.17 mp_allowmonsters 0 mp_autokick 0 mp_autoteambalance 1 mp_buytime 1.5 mp_c4timer 35 mp_chattime 10 mp_consistency 1 mp_fadetoblack 0 mp_flashlight 0 mp_footsteps 1 mp_forcecamera 0 mp_forcechasecam 0 mp_fragsleft 0 mp_freezetime 7 mp_friendlyfire 0 mp_ghostfrequency 0.4 mp_hostagepenalty 3 mp_kickpercent 0.66 mp_limitteams 1 mp_logdetail 0 mp_logfile 1 mp_logmessages 1 mp_mapvoteratio 0.66 mp_maxrounds 0 mp_mirrordamage 0 mp_playerid 0 mp_roundtime 3 mp_startmoney 800 mp_timeleft 0 mp_timelimit 30 mp_tkpunish 0 mp_windifference 1 mp_winlimit 0 pausable 0 public_slots_free 16 reserve_slots 0 reserve_type 0 sv_accelerate 5 sv_aim 0 sv_airaccelerate 3 sv_airmove 1 sv_allowupload 1 sv_alltalk 0 sv_bounce 1 ';


/* Call function */
$p = new Net_GameServerQuery_ParseString;
print_r($p->parse($string, $rules));

?>
