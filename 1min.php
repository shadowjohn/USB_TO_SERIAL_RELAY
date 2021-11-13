<?php
    //�C�����ˬd�@�������O�_���`
    //��ʤ֪��禡������A�p�U��ڭn�a~
    //�Фs�� Email : linainverseshadow@gmail.com
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
              //�s�u���`�A�g�JDB
              //�����s�u�ɶ�
              $m['delay_ms'] = trim(end(explode("time=",$jd['data'][1])));
              $m['delay_ms'] = trim(str_replace("ms","",$m['delay_ms']));              
              insertSQL('connection',$m);
            }
            break;
        case "0":
            {
              //�եX�̫�10������ơA�p�G�s�T�� on_off = 0�A�B�o10���S���X�{�L is_call_stop_modem='1'
              //�N���}�A��
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
                //��Ʈw�O�����Ƥ���10��
                insertSQL('connection',$m);
              }
              else
              {
                if($ra[0]['on_off']=='0' && $ra[1]['on_off']=='0' && $ra[2]['on_off']=='0')
                {                    
                    //�p�G10�����A���X�{ is_call_stop_modem='1'�A�N���L
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
                        //10�������w�����L�@���p�Q�t
                        insertSQL('connection',$m);
                    }
                    else
                    {
                        //���p�Q�t
                        $m['is_call_stop_modem']='1';                        
                        //�I�s python ���p�Q�t�A�� 15 ��
                        $CMD = "cd \"{$PWD}\" && /usr/bin/python serialOnOff.py /dev/ttyUSB0 on 15000";
                        $m['notes']=`{$CMD}`;
                        insertSQL('connection',$m);                        
                    }                    
                }
                else
                {
                    //�٤������ѤT��
                    insertSQL('connection',$m);
                }
              }  
            }
            break;
    } 
    