<?php
/**
 * Command-line tool to list docs in  Solr index.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind
 * @package  Utilities
 * @author   Richard Aroksaar  richard_aroksaar@nps.gov
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org/wiki/automation Wiki
 */

 $bibid = isset($argv[1]) ? $argv[1] : null;
 if (empty($bibid)) {
    echo "Retrieve records from VuFind by bib i.d. .\n";
    echo "Usage: php ParseSolrMarc.php [bib i.d. number]\n";
    die();
 }

/**
 * Set up util environment, this and solr connection protocol 
 * borrowed from deletes.php written by
 * Andrew Nagy and Demian Katz
 */
 
//error_reporting(E_ALL); 
//ini_set('display_errors', 1); 

require_once 'util.inc.php';        
require_once 'sys/ConnectionManager.php';


// This MarcRecord.php is from
// https://github.com/KDK-Alli/RecordManager/blob/master/classes/MarcRecord.php
// maintained by Ere Maijala of the Finnish National Library.
// It can be copied into a new directory called /RecordManager under usr/local/vufind
// it has its own references to Logger.php, BaseRecord.php and MetadataUtils.php 
// which are also available from
// https://github.com/KDK-Alli/RecordManager/tree/master/classes 
// and should be copied into the same directory as MarcRecord.php
//
// This should not be confused by the MarcRecord.php that comes bundled with 
// VuFind and is in /usr/local/vufind/web/RecordDrivers with a standard
// installation. This version seems designed to support VuFind interface 
// functionality whereas the version being shown here is more of a classic parser

// if the RecordManager folder has been created under 
// usr/local/vufind/web/ then the following enables the functionality 
// of the basic parser
// require_once 'RecordManager/MarcRecord.php';

// MyMarcRecord.php is a subclassed version of the above that lets us modify 
// functionality without tampering with the original and this code spells out 
// the full path
require_once '/usr/local/vufind/RecordManager/MyMarcRecord.php';

// Read Config file
$configArray = readConfig();

// Setup Local Database Connection
ConnectionManager::connectToDatabase();

// Setup Solr Connection
$solr = ConnectionManager::connectToIndex();
$result = $solr->search('id:' . $bibid, 'null', 'null', '0', '10');


if ($result['response']['numFound'] > 0) {
    $cite = $result['response']['docs'][0];
    // the following are solr Marc fields that can be easily displayed
    // error handling needs to still deal with cases when there is no 
    // value in $cite['publisher'][0]
    echo $cite['id'];
    echo ". " . $cite['title'] . " \n";  
	echo "\t" . $cite['publisher'][0] . $cite['publishDate'][0] . "\n" ;

    // now instantiate a MarcRecord object named $r because MyMarcRecord
    // inherits all MarcRecord functionality so the parent class can be used
	$r = new MarcRecord($cite['fullrecord'],"","","");
	echo "\t" . $r->getFormat() . "\n";
        
    // use subclassed verison to do more:
    $MyR = new MyMarcRecord($cite['fullrecord'],"","","");

    // getFieldsByTag is a public function that is 
    // defined in MyMarcRecord
	$field650s = $MyR->getFieldsByTag("650");	
	foreach ($field650s  as $field650) {
	   echo "\t" . "Subject: " . $field650;	
	    }
	echo "\t============\n";
    // holdings are in repeatable 852 fields in database being used
	$field852s = $MyR->getFieldsByTag("852");	
	foreach ($field852s  as $field852) {
	   echo "\t" . "Holding: " .  $field852;	
	    }
        echo "\t============\n";
    }
    else
    {
        echo "Nothing found!\n";
    }

?>
