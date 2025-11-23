<?php
echo "pruebas redis"
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
echo "OK\n";