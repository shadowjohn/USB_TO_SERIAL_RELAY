# USB_TO_SERIAL_RELAY

<h2>功能：</h2>
利用 Serial 控制 Relay 讓中華電信的數據機斷電一段時間，再重新復電
<br>
<h2>作者：</h2>
    羽山秋人 ( https://3wa.tw )
<br>
<br>
<h2>版本：</h2>
    V0.01
<br>
<br>
<h2>動機：</h2>
由於最近中華的小烏龜三不五十當機，只能斷電再開機才能回復正常，USB 轉 Serial 接 Relay，利用程式來切斷電源跟回復電源。
<br>
<br>
<img src="screenshot/s1.png">
<center>
    完成的圖
</center>
<br>
<img src="screenshot/s3.png">
<center>
    電路圖
</center>
<br>
<img src="screenshot/s2.png">
<center>
    使用方式
</center>
<br>
<h2>程式開發語言：</h2>
    python 2.7 (Linux)<br>
    python 3.9 x86 (Windows)<br>

<h2>相依性：</h2>
    pip install pyserial
<br>
<br>
<h2>使用方法：</h2>
    python serialOnOff.py<br>
    python serialOnOff.py [Com Port] [on/off] [millisecond]<br>
    例如：<br>
    python serialOnOff.py com7 on 15000<br>    
    如果在 Linux 下<br>
    python serialOnOff.py /dev/ttyUSB0 on 15000<br>
<br>
<h2>詳細說明：</h2>
    <a href="https://3wa.tw/blog/blog.php?id=1942">https://3wa.tw/blog/blog.php?id=1942</a>    
    
