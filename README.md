VuFindReport
============

php code to query VuFind solr index and make listings

VuFindReport.php is a basic php script that can be used for reporting
in a VuFind context. It works with both Windows and Linux installations.

Basic usage: from the /vufind/util directory enter:
    php VuFindReport.php
to see results in a command window
or 
    php VuFindReport.php > list.txt
to send the results to a file. The latter seems to handle utf-8 
character encoding correctly whereas
the terminal window may not do that.

This basic scipt provides a useful way of exploring solr MARC 
fields and also permits  testing php functionality.
