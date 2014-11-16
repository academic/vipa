0 */2 * * * php [OJS_DIRECTORY]/app/console ojs:jobqueue:add ojs:count:journals:subjects

0 */4 * * * php [OJS_DIRECTORY]/app/console ojs:jobqueue:add fos:elastica:populate 
