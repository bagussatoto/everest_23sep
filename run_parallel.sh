#!/bin/bash

echo 'Starting Parallel Process with ID Range Strategy...'
mkdir -p /var/www/everest/logs

echo 'Starting Thread 0 (ID: 1 - 29032)'
php index.php UpdateDtime_parallel process_range 1 29032 > /var/www/everest/logs/thread_0.log 2>&1 &
echo $! > /var/www/everest/logs/thread_0.pid
echo 'Starting Thread 1 (ID: 29032 - 58063)'
php index.php UpdateDtime_parallel process_range 29032 58063 > /var/www/everest/logs/thread_1.log 2>&1 &
echo $! > /var/www/everest/logs/thread_1.pid
echo 'Starting Thread 2 (ID: 58063 - 87094)'
php index.php UpdateDtime_parallel process_range 58063 87094 > /var/www/everest/logs/thread_2.log 2>&1 &
echo $! > /var/www/everest/logs/thread_2.pid
echo 'Starting Thread 3 (ID: 87094 - 116125)'
php index.php UpdateDtime_parallel process_range 87094 116125 > /var/www/everest/logs/thread_3.log 2>&1 &
echo $! > /var/www/everest/logs/thread_3.pid
echo 'Starting Thread 4 (ID: 116125 - 145156)'
php index.php UpdateDtime_parallel process_range 116125 145156 > /var/www/everest/logs/thread_4.log 2>&1 &
echo $! > /var/www/everest/logs/thread_4.pid
echo 'Starting Thread 5 (ID: 145156 - 174187)'
php index.php UpdateDtime_parallel process_range 145156 174187 > /var/www/everest/logs/thread_5.log 2>&1 &
echo $! > /var/www/everest/logs/thread_5.pid
echo 'Starting Thread 6 (ID: 174187 - 203218)'
php index.php UpdateDtime_parallel process_range 174187 203218 > /var/www/everest/logs/thread_6.log 2>&1 &
echo $! > /var/www/everest/logs/thread_6.pid
echo 'Starting Thread 7 (ID: 203218 - 232249)'
php index.php UpdateDtime_parallel process_range 203218 232249 > /var/www/everest/logs/thread_7.log 2>&1 &
echo $! > /var/www/everest/logs/thread_7.pid
echo 'Starting Thread 8 (ID: 232249 - 261280)'
php index.php UpdateDtime_parallel process_range 232249 261280 > /var/www/everest/logs/thread_8.log 2>&1 &
echo $! > /var/www/everest/logs/thread_8.pid
echo 'Starting Thread 9 (ID: 261280 - 290311)'
php index.php UpdateDtime_parallel process_range 261280 290311 > /var/www/everest/logs/thread_9.log 2>&1 &
echo $! > /var/www/everest/logs/thread_9.pid
echo 'Starting Thread 10 (ID: 290311 - 319342)'
php index.php UpdateDtime_parallel process_range 290311 319342 > /var/www/everest/logs/thread_10.log 2>&1 &
echo $! > /var/www/everest/logs/thread_10.pid
echo 'Starting Thread 11 (ID: 319342 - 348373)'
php index.php UpdateDtime_parallel process_range 319342 348373 > /var/www/everest/logs/thread_11.log 2>&1 &
echo $! > /var/www/everest/logs/thread_11.pid
echo 'Starting Thread 12 (ID: 348373 - 377404)'
php index.php UpdateDtime_parallel process_range 348373 377404 > /var/www/everest/logs/thread_12.log 2>&1 &
echo $! > /var/www/everest/logs/thread_12.pid
echo 'Starting Thread 13 (ID: 377404 - 406435)'
php index.php UpdateDtime_parallel process_range 377404 406435 > /var/www/everest/logs/thread_13.log 2>&1 &
echo $! > /var/www/everest/logs/thread_13.pid
echo 'Starting Thread 14 (ID: 406435 - 435466)'
php index.php UpdateDtime_parallel process_range 406435 435466 > /var/www/everest/logs/thread_14.log 2>&1 &
echo $! > /var/www/everest/logs/thread_14.pid
echo 'Starting Thread 15 (ID: 435466 - 464497)'
php index.php UpdateDtime_parallel process_range 435466 464497 > /var/www/everest/logs/thread_15.log 2>&1 &
echo $! > /var/www/everest/logs/thread_15.pid
echo 'Starting Thread 16 (ID: 464497 - 493528)'
php index.php UpdateDtime_parallel process_range 464497 493528 > /var/www/everest/logs/thread_16.log 2>&1 &
echo $! > /var/www/everest/logs/thread_16.pid
echo 'Starting Thread 17 (ID: 493528 - 522559)'
php index.php UpdateDtime_parallel process_range 493528 522559 > /var/www/everest/logs/thread_17.log 2>&1 &
echo $! > /var/www/everest/logs/thread_17.pid
echo 'Starting Thread 18 (ID: 522559 - 551590)'
php index.php UpdateDtime_parallel process_range 522559 551590 > /var/www/everest/logs/thread_18.log 2>&1 &
echo $! > /var/www/everest/logs/thread_18.pid
echo 'Starting Thread 19 (ID: 551590 - 580621)'
php index.php UpdateDtime_parallel process_range 551590 580621 > /var/www/everest/logs/thread_19.log 2>&1 &
echo $! > /var/www/everest/logs/thread_19.pid
echo 'Starting Thread 20 (ID: 580621 - 609652)'
php index.php UpdateDtime_parallel process_range 580621 609652 > /var/www/everest/logs/thread_20.log 2>&1 &
echo $! > /var/www/everest/logs/thread_20.pid
echo 'Starting Thread 21 (ID: 609652 - 638683)'
php index.php UpdateDtime_parallel process_range 609652 638683 > /var/www/everest/logs/thread_21.log 2>&1 &
echo $! > /var/www/everest/logs/thread_21.pid
echo 'Starting Thread 22 (ID: 638683 - 667714)'
php index.php UpdateDtime_parallel process_range 638683 667714 > /var/www/everest/logs/thread_22.log 2>&1 &
echo $! > /var/www/everest/logs/thread_22.pid
echo 'Starting Thread 23 (ID: 667714 - 696737)'
php index.php UpdateDtime_parallel process_range 667714 696737 > /var/www/everest/logs/thread_23.log 2>&1 &
echo $! > /var/www/everest/logs/thread_23.pid

echo 'All threads started. Monitor logs in /logs folder.'
