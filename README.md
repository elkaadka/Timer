![build](https://travis-ci.org/elkaadka/Timer.svg?branch=master)

A simple Timer to benchmark functions or code execution duration

#How it works :

 - Start the timer
     ```
         Timer::start();     
      ```
 - Mark a place as a lap (the timer will continue counting time after returning the difference between the start and this lap)
   ```
       $duration = Timer::lap();
    ```
   where $duration is in milliseconds
           
 - If you want the duration between this lap and the last one, send true as a parameter:
   ```
       $duration = Timer::lap(Timer::FROM_LAST_LAP);
    ```
 - To stop the time and get the duration from the beginning (the start)
    ```
        $duration = Timer::stop();
     ```
 - To stop the time and get the duration from the last lap 
    ```
        $duration = Timer::stop(Timer::FROM_LAST_LAP);
    ```