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


/**
 * Set up util environment, this and solr connection protocol 
 * borrowed from deletes.php written by
 * Andrew Nagy and Demian Katz
 */
require_once 'util.inc.php';        
require_once 'sys/ConnectionManager.php';

// Read Config file
$configArray = readConfig();

// Setup Local Database Connection
ConnectionManager::connectToDatabase();

// Setup Solr Connection
$solr = ConnectionManager::connectToIndex();

// to query authority database, specify 'SolrAuth' as parameter:
//$solr = ConnectionManager::connectToIndex('SolrAuth');


// See vufind/web/sys/Solr.php, for parameters for solr query:
//    public function search($query, $handler = null, $filter = null, $start = 0,
//        $limit = 20, $facet = null, $spell = '', $dictionary = null,
//        $sort = null, $fields = null,
//        $method = HTTP_REQUEST_METHOD_POST, $returnSolrError = false

// a simple wildcard query that retrieves the first page, 
// consisting of 10 citations, 
// of results for all solr documents, looks like this:
$result = $solr->search('*:*', 'null', 'null', '0', '10');

// This is an example of a query that specifies an index 
// and a specific value, 
// i.e., the solr document with an i.d. value of 3727
// $result = $solr->search('id:3727');
$nTotal = $result['response']['numFound'];

$nSets = ($nTotal/10) + 1; 
for ($i = 0; $i <= $nSets; $i++) {
    // within the loop the query needs to match initial query 
    // except with a start page that is incremented,
    // i.e. the loop counter $i times 10
    $result = $solr->search('*:*', 'null', 'null', $i*10, '10');
    $cites = $result['response']['docs'];

    // this loop lists the specified fields for each of the 
    // 10 solr documents retrieved, in this case the 
    // i.d. number and the title
    // see /usr/local/vufind/import/marc.properties 
    // for fields that can be listed, 
    // as well as the indexes that can be specified as the 
    // first part of the query

    // 'fullrecord' refers to the raw MARC record, so a loop 
    // that specifies  $cite['fullrecord'] without any tabs 
    // or newlines creates a dump of MARC records

    // to write the results to a file use this 
    // from the command line:
    // /usr/local/vufind/util$ php VuFindReport.php > list.txt

    foreach ($cites as &$cite){
        echo $cite['id'] . "\t ";
        echo $cite['title'] . " \n";
        }
    }
echo "========== " . $nTotal . " ================\n";
?>
