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
// | Authors: Aidan Lister <aidan@php.net>                                |
// |          Tom Buskens <ortega@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id$


require_once 'GameServerQuery\Protocol.php';


/**
 * GameSpy04 Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_gamespy04 extends Net_GameServerQuery_Protocol
{

    /**
     * Conversion class
     *
     * @access     private
     * @var        resource
     */
    private $_convert;


    /**
     * Constructor
     *
     * @access     public
     */ 
    public function __construct()
    {
        // Initialize conversion class
        $this->_convert = new Net_GameServerQuery_Convert;

        // Define packets
        $this->_packets = array(
            'rules'   => "\xfe\xfd\x00NGSQ\xff\x00\x00",
            'players' => "\xfe\xfd\x00NGSQ\x00\xff\xff",
            'team'    => "\xfe\xfd\x00NGSQ\x00\x00\xff"
        );
        
        // Define packet mapping array
        $this->_map = array(
            'ping'    => 'team',
            'players' => 'players',
            'rules'   => 'rules',
        );
    }


    /**
     * Rules packet
     *
     * @access    protected
     * @return    array      Array containing formatted server response
     */
    protected function _rules()
    {
        // Header
        if (!$this->_match("\\x00NGSQ")) {
            return false;
        }

        // Variable / value pairs
        while ($this->_match("([^\\x00]+)\\x00([^\\x00]*)\\x00")) {
            $this->_addVar($this->_result[1], $this->_result[2]);
        }

        return $this->_output;
        
    }


    /**
     * Player packet
     *
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _players()
    {
        // Header
        if (!$this->_match("\\x00NGSQ")) {
            return false;
        }
        // Number of players
        if (!$this->_match("\\x00(.)")) {
            return false;
        }
        $player_count = $this->_convert->toInt($this->_result[1], 8);
        $this->_addVar('playercount', $player_count);
     
        // Get variable names
        $variables = array();
        
        while (true) {
            if (!$this->_match("([^\\x00]+)\\x00")) {
                return false;
            }
            
            // Save variable name
            array_push($variables, $this->_result[1]);

            // Check for second \x00
            if ($this->_match("\\x00")) {
                break;
            }
            
        }

        // Get values
        $var_count = count($variables);
        
        for ($i = 0; $i !== $player_count; $i++) {
            for ($j = 0; $j !== $var_count; $j++) {
                if (!$this->_match("([^\\x00]+)\\x00")) {
                    return false;
                }
                $this->_addVar($variables[$j], $this->_result[1]);
            }
        }

        // Get team info (same packet)
        $this->_team(true);
        
        return $this->_output;
        
    }

    /**
     * Team packet
     *
     * @access     protected
     * @param      bool  $from_players  True if packet was also contained player data.
     * @return     array Array containing formatted server response
     */
    protected function _team($from_players = false)
    {
        // Header
        if (!$from_players && !$this->_match("\\x00NGSQ")) {
            return false;
        }

        // Number of teams
        if (!$this->_match("\\x00(.)")) {
            return false;
        }
        $team_count = $this->_convert->toInt($this->_result[1], 8);
        $this->_addVar('teamcount', $team_count);
     
        // Get variable names
        $variables = array();
        
        while (true) {
            if (!$this->_match("([^\\x00]+)\\x00")) {
                return false;
            }
            
            // Save variable name
            array_push($variables, $this->_result[1]);

            // Check for second \x00
            if ($this->_match("\\x00")) {
                break;
            }
            
        }

        // Get values
        $var_count = count($variables);
        
        for ($i = 0; $i !== $team_count; $i++) {
            for ($j = 0; $j !== $var_count; $j++) {
                if (!$this->_match("([^\\x00]+)\\x00")) {
                    return false;
                }
                $this->_addVar($variables[$j], $this->_result[1]);
            }
        }

        return $this->_output;
        
    }

}

?>
