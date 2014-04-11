<?php
require_once 'MarcRecord.php';

class MyMarcRecord extends MarcRecord
{
   protected $fields;

     /**
     * Retrieves the full title as a test of the inherited protected
     * function: getFieldSubfields
     *
     * @return string[]
     * @access public
     */
   public function getFullTitle()
    {
        return $this->getFieldSubfields('245');
    }
	
     /**
     * Retrieves fields with a specified tag as a simple array of strings, with subfields concatenated
     *
     * @return string[]
     * @access public
     */
    public function getFieldsByTag($tagvalue)
    {
	    // use the inherited protected getFields function from MarcRecord.php:
       
		$results = $this->getFields($tagvalue);
        // use var_dump when testing to see what $results looks like:
        // var_dump($results);
		$stringlist = array();

        // convert $result into a simple list of strings		
		foreach ($results  as $onefield) {
            $temp = "";
	        foreach ($onefield["s"]  as $subfield) {
		        $subkeys = array_keys($subfield);
			    foreach ($subkeys as $onekey){
				    $subname = $onekey;
				    $temp = $temp . "|" . $onekey . $subfield[$onekey];
			        }
                }
			$stringlist[]=$temp . "\n";	
        }

	
    	return $stringlist;
    }
	
	
    /**
     * Get first matching field
     * 
     * @param string $field Tag to get
     * 
     * @return string
     *
     * This is a copy of the protected getField function in MarcRecord.php
     * but declared public so it will be available from the actual working script
     * rather than being only available within the class
     */
    public function getField($field)
    {
        if (isset($this->fields[$field])) {
            if (is_array($this->fields[$field])) {
                return $this->fields[$field][0];
            } else {
                return $this->fields[$field];
            }
        }
        return '';
    }

}
