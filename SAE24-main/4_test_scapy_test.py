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

for frame in frames:
    if frame.haslayer('TCP') and frame.haslayer('Raw'):
        data = frame[Raw].load
        if "USER" in str(data):
            print(frames.index(frame)+1, str(data))
        if "PASS" in str(data):
            print(frames.index(frame)+1, str(data))
        if "SYST" in str(data):
            print(frames.index(frame)+1, str(data))
        if "PORT" in str(data):
            print(frames.index(frame)+1, str(data))
        if "RETR" in str(data):
            print(frames.index(frame)+1, str(data))


