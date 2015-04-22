```
0 */2 * * * php [OJS_DIRECTORY]/app/console ojs:jobqueue:add ojs:count:journals:subjects
0 */2 * * * php [OJS_DIRECTORY]/app/console ojs:jobqueue:add ojs:count:common

## if ojs:jobqueue is not running as a service you should add each job 
# 0 */2 * * * php [OJS_DIRECTORY]/app/console  ojs:count:journals:subjects
# 0 */2 * * * php [OJS_DIRECTORY]/app/console  ojs:count:common

0 */4 * * * php [OJS_DIRECTORY]/app/console ojs:jobqueue:add fos:elastica:populate 
```
