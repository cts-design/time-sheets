<?php 
#seconds to sleep() when no executable job is found
$config['queue']['sleeptime'] = 10;

#Propability in percent of a old job cleanup happening
$config['queue']['gcprop'] = 20;

#Default timeout after which a job is requeued if the worker doesn’t report back
$config['queue']['defaultworkertimeout'] = 120;

#Default number of retries if a job fails or times out.
$config['queue']['defaultworkerretries'] = 4;

#Seconds of runnig time after which the worker will terminate (0 = unlimited)
$config['queue']['workermaxruntime'] = 0;

#Should a Workerprocess quit when there are no more tasks for it to execute (true = exit, false = keep running)
$config['queue']['exitwhennothingtodo'] = false;
