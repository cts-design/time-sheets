#!/bin/bash

# runBeanstalkd-worker.sh

# a shell script that keeps looping until an exit code is given
# if it does an exit(0), restart after a second - or if it's a declared error
# if we've restarted in a planned fashion, we don't bother with any pause
# and for one particular code, exit the script entirely.
# The numbers 97, 98, 99 must match what is returned from the PHP script

/var/www/vhosts/staging.atlasforworkforce.com/atlas/shared/cakephp/cake/console/cake -app /var/www/vhosts/staging.atlasforworkforce.com/atlas/current queue email_worker -- $@
#cake queue email_worker  -- $@

ERR=$?

## Possibilities
# 97    - planned pause/restart
# 98    - planned restart
# 99    - planned stop, exit.
# 0     - unplanned restart (as returned by "exit;")
#        - Anything else is also unplanned paused/restart

if [ $ERR -eq 97 ]
then
   # a planned pause, then restart
   echo "97: PLANNED_PAUSE - wait 1";
   sleep 1;
   exec $0 $@;
fi

if [ $ERR -eq 98 ]
then
   # a planned restart - instantly
   echo "98: PLANNED_RESTART";
   exec $0 $@;
fi

if [ $ERR -eq 99 ]
then
   # planned complete exit
   echo "99: PLANNED_SHUTDOWN";
   exit 0;
fi

# unplanned exit, pause, and restart
echo "unplanned restart: err:" $ERR;
echo "sleeping for 1 sec"
sleep 1

exec $0 $@
