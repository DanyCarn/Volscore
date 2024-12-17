<?php

require_once 'Model.php';
require_once 'Team.php';
require_once 'Game.php';
require_once 'Member.php';
require_once 'Set.php';
require_once 'Point.php';
require_once 'TimeInThe.php';

interface IVolscoreDb {
#region Equipes et joueurs

    /**
     * Get all teams in the Db
     * returns an array of objects of the Team type 
     */
    public static function getTeams() : array;

    /**
     * Get a specific team
     * parameter $number is the team number
     * returns a Team object
     */
    public static function getTeam($number) : Team;

    /**
     * Rename a team
     * Return false if the name was invalid or already existing
     */
    public static function renameTeam($number, $newname) : bool;

    /**
     * Tells if a team has already played a game
     */
    public static function teamHasPlayed($team) : bool;

    /**
     * Get all members of a given team
     */
    public static function getMembers($teamid) : array ;

    /**
     * Get all players of a given team
     */
    public static function getMember($memberid) : ?Member ;

    /**
     * Get the captain of a specific team
     * parameter $team is a Team object
     * returns a Member object
     */
    public static function getCaptain($team) : Member;
    
    /**
     * Get the libero of a specific team
     * parameter $team is a Team object
     * returns a Member object
     */
    public static function getLibero($team) : Member;
    
    /**
     * Supprime une équipe
     * Cette commande ne fonctionne que si l'équipe n'a commencé aucun match.
     * Si ce n'est pas le cas, elle est supprimée, ainsi que tous ses membres et les matches planifiés
     */
    public static function deleteTeam ($teamid) : bool;

    #endregion
#region Matches
    /**
     * Get all games in the system
     * returns an array of Game objects, sorted by game time, older first
     */
    public static function getGames() : array;

    /**
     *Retourne la liste des matches qui sont dans une période donnée
     *Les matches du 'Present' sont les matches d'aujourd'hui, quelle que soit l'heure.
     *Un match qui se passe ce soir à 20h00 sera donc selectionné à 16h00 par 'Present' et 'Future' 
     *$period : Une des valeurs de l'énum TimeInThe: 'Past', 'Present', 'Future'
     *Retourne: Une liste triée par date/heure, le match le plus ancien en premier
     */
    public static function getGamesByTime($period) : array ;
    
    /**
     * Returns a Game object built with the data fetched in the db
     */
    public static function getGame($number) : ?Game; 
    
    /**
     * Updates an existing game
     */
    public static function saveGame($game);

    /**
     * Returns true if there is a winner (based on the points scored )
     */ 
    public static function gameIsOver($game) : bool;

    /**
     * Create a new game in the db based on the Game object passed
     * The value returned is the game number.
     * If the game has not been created, the value returned is null
     */ 
    public static function createGame($game) : ?int; 

    /**
     * Add a new set to the game
     */ 
    public static function addSet($game) : Set;

    /**
     * Add a point to the set
     * The point is added to the receiving team if $receiving == true, to the visiting team otherwise
     */ 
    public static function addPoint($set, $receiving);

    /**
     * Add a timeout to the given team in the given set
     */
    public static function addTimeout($teamid, $setid);
    
    /**
     * Puts the current time as end time od the set 
     * Note: the start time is saved when the very first point of the set is scored
     */
    public static function registerSetEndTimestamp($setid);

    /**
     * Get the timeouts of a team in a set
     */    
    public static function getTimeouts($teamid,$setid) : array;
    
    /**
     * returns all the sets of the given game
     */ 

    public static function getSets($game) : array;
    
    /**
     * Returns the number of sets the game has (0-5)
     */  
    public static function numberOfSets($game) : int;

    /**
     * Returns a specific set 
     */ 
    public static function getSet($setid) : Set;

    /**
     * Returns the scores for each player in the set
     */
    public static function getSetScoringSequence($set) : array;
    
    /**
     * Returns the player who will serve the next point in the set
     */
    public static function nextServer($set) : Member;
    
    /**
     * Returns true if the set is finished (based on the points scored, including 2 points lead and 5th set at 15 )
     */  
    public static function setIsOver($set) : bool;

    public static function getBenchPlayers($gameid,$setid,$teamid); //#### Not Implemented

    /**
     * Returns the list of members who have been registered as players for the
     * given team in the given game (liste d'engagement)
     */
    public static function getRoster($gameid, $teamid) : array;

    /**
     * Certifies that the player is present, showed his license and has the correct number
     * on his shirt for the given game 
     * ATTENTION: we pass the memberid which must match members.id, NOT players.id
     */
    public static function validatePlayer($gameid,$memberid);

    /**
     * Stores the players starting positions for a specific team in a specific set
     * $final == 1 indicates that no more changes will be allowed
     */
    public static function setPositions($setid, $teamid, $pos1, $pos2, $pos3, $pos4, $pos5, $pos6, $final);

    /**
     * Updates the existing players starting positions for a specific team in a specific set
     * $final == 1 indicates that no more changes will be allowed
     */
    public static function updatePositions($setid, $teamid, $pos1, $pos2, $pos3, $pos4, $pos5, $pos6, $final);

    /**
     * Get the players starting positions for a specific team in a specific set
     * If $setid == 0 , the last positions used by that team are returned
     */
    public static function getPositions($setid, $teamid) : array;

    /**
     * Takes a member of a team and links him to a specific game, 
     * making a player out of him in the process
     * Returns true if it was possible
     */
    public static function makePlayer($memberid,$gameid) : bool;

    /**
     * Returns the member info in the context of a specific game
     * Or null if the member was not part of that game
     */
    public static function getPlayer($memberid,$gameid) : ?Member;

    /**
     * Registers a booking against a player in a given set
     * The booking will be automatically linked to last point played in the set at this time
     * - severity           0=yellow, 1=red, 2=yellow and red together, 3=yellow and red separate
     */
    public static function giveBooking ($playerid, $setid, $severity);

    /**
     * Returns the list of bookings for a given team in a given game
     * The result is an array of associative arrays.
     * Each booking is one associative array with keys:
     * - timestamp          Time of the point after which the booking was given
     * - number             of the player
     * - last_name          of the player
     * - severity           0=yellow, 1=red, 2=yellow and red together, 3=yellow and red separate
     * - scoreReceiving     Score at the moment of the booking
     * - scoreVisiting 
     */
    public static function getBookings($team,$set) : array;

    public static function saveTeam($teamName);

#endregion
}