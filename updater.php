<?php
	//将下面的信息修改为DNS-O-Matic的用户信息
	$username = "123";
	$password = "123";
	$hostname[] = "a.example.com";		//在dnsomatic添加服务，然后在这里填写域名
	$hostname[] = "b.example.net";
	$hostname[] = "c.example.org";

	$ip = get_publicIP();

	if($ip == "0")
	{
		echo "Error!Cann't get public IP!\n";
	}else
	{
		for($i=0;$i<count($hostname);$i++)
		{
			$dnsomaticapi = "https://$username:$password@updates.dnsomatic.com/nic/update?hostname=$hostname[$i]&myip=$ip&wildcard=NOCHG&mx=NOCHG&backmx=NOCHG";
			echo $hostname[$i]." ".http_request($dnsomaticapi)."\n";
		}
	}

	function http_request($url, $data = null)
	{
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}

	function get_publicIP()
	{
		$json = file_get_contents("http://api.k780.com/?app=ip.local&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json");
		$info = json_decode($json,true);
		if($info["success"]=="1")
		{
			$result = $info["result"];
			return $result["ip"];
		}else
		{
			$json = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=myip");
			$info = json_decode($json,true);
			if($info["code"]=="0")
			{
				$data = $info["data"];
				return $data["ip"];
			}else{
				return "0";
			}
		}
	}
?>
