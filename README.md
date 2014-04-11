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

The second script, ParseMarcrecord.php, shows how to use a Marc parser from
the Finnish National Library by subclassing it as MyMarcRecord.php.

It is designed to retrieve a single citation where the bib i.d. has been passed as a 
command line parameter:
    php ParseMarcRecord.php 1000

This is an example of output:

richard@library:/usr/local/vufind/util$ php ParseMarcRecord.php 3545
3545. The Tao te ching of Lao Tzu : a new translation /
        St. Martin's Press,1995
        Book
        ============
        Holding: |aIslandwood|bIn Library|cBL 190 .L26 E5 1995
        ============
richard@library:/usr/local/vufind/util$



