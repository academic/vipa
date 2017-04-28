```
0 */2 * * * php [VIPA_DIRECTORY]/app/console vipa:jobqueue:add vipa:count:journals:subjects
0 */2 * * * php [VIPA_DIRECTORY]/app/console vipa:jobqueue:add vipa:count:common

## if vipa:jobqueue is not running as a service you should add each job 
# 0 */2 * * * php [VIPA_DIRECTORY]/app/console  vipa:count:journals:subjects
# 0 */2 * * * php [VIPA_DIRECTORY]/app/console  vipa:count:common

0 */4 * * * php [VIPA_DIRECTORY]/app/console vipa:jobqueue:add fos:elastica:populate 
```
