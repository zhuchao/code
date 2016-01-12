<?php
$ystd = strtotime('-1 day'， $postData[0]['startTime']); // 前一天这个时刻的时间戳
$ystd = date("Y-m-d", $ystd); // 昨天的日期
$ystd = strtotime($ystd . " 17:00:00");//昨天下午5点


==============================================
$ystd = strtotime('-1 day'); // 昨天这个时刻的时间戳
$ystd = date("Y-m-d", $ystd); // 昨天的日期d

$ystd = strtotime($ystd." 00:00:00");//昨天凌晨
