
from scapy.all import *
import base64

frames = rdpcap("home_www-total.pcapng")

http_conection = []

for frame in frames:
    if frame.haslayer('TCP') and frame.haslayer('Raw'):
        if frame[2].dport == 80:
            data = str(frame[Raw].load)
            if "Authorization: Basic" in data:
                new = data.split("\\r")
                current = []
                for element in new:
                    if "Host: " in element:
                        current.append(element[8:])
                    if "Authorization" in element:
                        current.append(frame[1].src)
                        current.append(frame[1].dst)
                        userpass = base64.b64decode(element[23:]).decode('utf-8').split(":")
                        current.append(userpass[0])
                        current.append(userpass[1])

                if current not in http_conection:
                    http_conection.append(current)
                    print("----------------------------------------")
                    print("Connexion HTML")
                    print(f"Site: {current[0]} / IP(serv): {current[2]} / IP(client): {current[1]}")
                    print(f"User: {current[3]}")
                    print(f"Password: {current[3]}")

            if "POST" in data:
                new = data.split("\\r")
                current = []
                for element in new:
                    if "Referer: " in element:
                        current.append(element[11:])
                    if "username" in element:
                        current.append(frame[1].src)
                        current.append(frame[1].dst)
                        logform = element.split("&")
                        current.append(logform[1][9:])
                        current.append(logform[0][11:])

                if current not in http_conection:
                    http_conection.append(current)
                    print("----------------------------------------")
                    print("Connexion HTML.")
                    print(f"Site: {current[0]} / IP(serv): {current[2]} / IP(client): {current[1]}")
                    print(f"User: {current[3]}")
                    print(f"Password: {current[3]}")


