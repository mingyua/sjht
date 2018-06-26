<?php 
set_time_limit(0);  //在有关数据库的大量数据的时候，可以将其设置为0，表示无限制。  
ob_end_clean();     //在循环输出前，要关闭输出缓冲区  

$data=[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16];
foreach($data as $v){
    echo  "#$v 完毕<hr>"; 
    usleep(50000); 
    echo  str_pad("", 10000); 
    flush(); 	
}

//for ($i = 1; $i <= 100; $i++) { 
//  echo  "#$i 完毕<hr>"; 
//  sleep(1); 
//  echo  str_pad("", 10000); 
//  flush(); 
//}
?> 