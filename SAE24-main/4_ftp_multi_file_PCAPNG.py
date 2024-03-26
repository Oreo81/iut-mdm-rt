from scapy.all import *

frames = rdpcap("ftp-total.pcapng")
# frames = rdpcap("home_test.pcapng")

def getFTPData(trames):
    for frame in trames:
        if frame.haslayer('TCP') and frame[2].dport in [20, 21]:
            print("-------------------------------------------------------------------------")
            print(f"La trame numéro {trames.index(frame)+1} contient du FTP : {frame.summary()}")
            print(f"Elle s'établie sur l'adresse IP:port {frame[1].src}:{frame[2].sport} du client")
            print(f"Du coté serveur c'est sur l'adresse IP:port {frame[1].dst}:{frame[2].dport}")
            print()

# getFTPData(frames)

file = []

FIN = 0x01
SYN = 0x02


for frame in frames:
    if frame.haslayer('TCP') and frame.haslayer('Raw'):
        data = frame[Raw].load
        if "RETR" in str(data):
            file.append([str(data)[7:-5],frames.index(frame)+1,frame[1].src])
        if "USER" in str(data):
            print(frames.index(frame)+1, str(data))
        if "PASS" in str(data):
            print(frames.index(frame)+1, str(data))


    if frame.haslayer('TCP'):
        try:
            if (frames.index(frame) == file[len(file)-1][1]) and (frame[1].src == file[len(file)-1][2] or frame[1].dst == file[len(file)-1][2]):
                F = frame['TCP'].flags
                if F & SYN:
                    file[len(file)-1].append(frame[2].sport)
                    file[len(file)-1].append(frame[2].dport)
        except:
            pass
                
# print(file)

def getfil_with_port_name(p,name):
    data_list = []
    for frame in frames:
        if frame.haslayer('TCP') and frame.haslayer('Raw'):
            if frame[2].sport == p or frame[2].dport == p:
                data = frame[Raw].load
                data_list.append(data)
    "file/",name.writelines(data_list)

print(file)
for fichier in file:
    fichier[0] = open(f"file/{fichier[0]}", "wb")
    getfil_with_port_name(fichier[4],fichier[0])
