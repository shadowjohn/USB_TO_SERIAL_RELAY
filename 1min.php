<?php
    //每分鐘檢查一次網路是否正常
    //對缺少的函式有興趣再私下跟我要吧~
    //羽山的 Email : linainverseshadow@gmail.com
    $PWD = dirname(__FILE__);
    require "{$PWD}/../../../../inc/config.php";    
    $jd = ping_str("168.95.1.1");
/*
[status] => OK
[data] => Array
    (
        [0] => PING 168.95.1.1 (168.95.1.1) 56(84) bytes of data.
        [1] => 64 bytes from 168.95.1.1: icmp_seq=1 ttl=251 time=9.66 ms
        [2] =>
        [3] => --- 168.95.1.1 ping statistics ---
        [4] => 1 packets transmitted, 1 received, 0% packet loss, time 0ms
        [5] => rtt min/avg/max/mdev = 9.655/9.655/9.655/0.000 ms
    )
*/    
    $m = ARRAY();
    $m['datetime'] = date("Y-m-d H:i:s");
    $m['on_off'] = ($jd['status']=="NO")?"0":"1";    
    switch($m['on_off'])
    {
        case "1":
            {
              //連線正常，寫入DB
              //紀錄連線時間
              $m['delay_ms'] = trim(end(explode("time=",$jd['data'][1])));
              $m['delay_ms'] = trim(str_replace("ms","",$m['delay_ms']));              
              insertSQL('connection',$m);
            }
            break;
        case "0":
            {
              //調出最後10次的資料，如果連三次 on_off = 0，且這10次沒有出現過 is_call_stop_modem='1'
              //就重開服務
              $SQL = "
                SELECT
                    `on_off`,
                    ifnull(`is_call_stop_modem`,'0') AS `is_call_stop_modem`                     
                FROM 
                    `connection`
                ORDER BY
                    `id` DESC
                LIMIT 10
              ";
              $ra = selectSQL_SAFE($SQL,ARRAY());
              if(count($ra)<10)
              {
                //資料庫記錄筆數不足10次
                insertSQL('connection',$m);
              }
              else
              {
                if($ra[0]['on_off']=='0' && $ra[1]['on_off']=='0' && $ra[2]['on_off']=='0')
                {                    
                    //如果10次內，曾出現 is_call_stop_modem='1'，就跳過
                    $is_call_stop_modem = false;
                    for($i=0,$max_i=count($ra);$i<$max_i;$i++)
                    {
                        if($ra[$i]['is_call_stop_modem']=='1')
                        {
                            $is_call_stop_modem = true;
                            break;
                        }
                    }
                    if($is_call_stop_modem)
                    {
                        //10分鐘內已關機過一次小烏龜
                        insertSQL('connection',$m);
                    }
                    else
                    {
                        //關小烏龜
                        $m['is_call_stop_modem']='1';                        
                        //呼叫 python 關小烏龜，關 15 秒
                        $CMD = "cd \"{$PWD}\" && /usr/bin/python serialOnOff.py /dev/ttyUSB0 on 15000";
                        $m['notes']=`{$CMD}`;
                        insertSQL('connection',$m);                        
                    }                    
                }
                else
                {
                    //還不足失敗三次
                    insertSQL('connection',$m);
                }
              }  
            }
            break;
    } 
    