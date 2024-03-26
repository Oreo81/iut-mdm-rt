from scapy.all import *

frames = rdpcap("telnet-total.pcapng")


connection = []
login = ""
password = ""

def check_ip_connection(ip,connec):
    for cxt in connec:
        if ip in connec:
            return connec.index(cxt)

def getFTPData(trames):
    for frame in trames:
        if connection != []:
            # idx = check_ip_connection(frame[1].dst,connection)
            if frame.haslayer('TCP') and frame[2].dport == 23 and frame[1].dst == connection[len(connection)-1][1]:
                if frame.haslayer('Raw'):
                    data = frame[Raw].load
                    print("Client",data)
                
            if frame.haslayer('TCP') and frame[1].src == connection[len(connection)-1][1]:
                if frame.haslayer('Raw'):
                    data = frame[Raw].load
                    if "Password" in str(data):
                        print("OUI")

        elif frame.haslayer('TCP') and (frame[2].sport == 23):
            if frame.haslayer('Raw'):
                data = frame[Raw].load
                if "login:" in str(data):
                    
                    if "Last login" in str(data):
                        pass
                    else:
                        connection.append([trames.index(frame)+1,frame[1].src,frame[2].sport])

                

    print("Login:",login)    
    print("Pass:",password)
    print(connection)



getFTPData(frames)