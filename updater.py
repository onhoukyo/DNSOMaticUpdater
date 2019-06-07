# -*- coding: utf-8 -*-
import urllib.request
import json

def get_publicIP() :
	jsondata = urllib.request.urlopen('http://api.k780.com/?app=ip.local&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json').read().decode('utf-8')
	info = json.loads(jsondata)
	if info['success'] :
		result = info['result']
		return result['ip']
	else :
		jsondata = urllib.request.urlopen('http://ip.taobao.com/service/getIpInfo.php?ip=myip').read().decode('utf-8')
		info = json.loads(jsondata)
		if info['code'] :
			result = info['data']
			return result['ip']
		else :
			return "0"


#将下面的信息修改为DNS-O-Matic的用户信息
username = '123'
password = '123'
hostname = ['a.example.com',
			'b.example.com',
			'c.example.com']

ip = get_publicIP()
apiurl = 'https://updates.dnsomatic.com'
password_mgr = urllib.request.HTTPPasswordMgrWithDefaultRealm()
password_mgr.add_password(None, apiurl, username, password)
handler = urllib.request.HTTPBasicAuthHandler(password_mgr)
opener = urllib.request.build_opener(handler)

if ip=="0" :
	print("Error!Cann't get public IP!")
else :
	for host in hostname:
		url = "https://updates.dnsomatic.com/nic/update?hostname="+host+"&myip="+ip+"&wildcard=NOCHG&mx=NOCHG&backmx=NOCHG"
		dnsomaticapi = opener.open(url).read().decode('utf-8')
		print(host+" "+dnsomaticapi)
		
