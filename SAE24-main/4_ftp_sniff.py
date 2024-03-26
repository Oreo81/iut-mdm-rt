from scapy.all import *
from collections import Counter

packet_counts = Counter()

file = []
data_cache = []
historique_file = []
nb_file_found = 0
user = []
mdp = []

def show_info(trame):
    key = tuple(sorted([trame[1].src, trame[1].dst]))
    packet_counts.update([key])
    # print(f"Current = {sum(packet_counts.values())} | fichier trouvé: {nb_file_found} | {historique_file} | {user} & {mdp} \r", end="")

    ftp(trame,sum(packet_counts.values()))

def ftp(frame,idx):
    global data_cache, nb_file_found
    F = frame['TCP'].flags

    if frame.haslayer('Raw'):   #Si le paquet contient des données
        data = frame[Raw].load
        if "RETR" in str(data): #Server --> Client transfert 
            file.append([str(data)[7:-5],idx,frame[1].src])
            historique_file.append(str(data)[7:-5])
            nb_file_found += 1
            print("--------------------------------------------")
            print(f"{idx}: ! Transfert trouver | Server --> Client")
            print(f"Client: {frame[1].src}:{frame[2].sport}")
            print(f"Server: {frame[1].dst}:{frame[2].dport}")

        if "STOR" in str(data): #Client --> Server transfert 
            file.append([str(data)[7:-5],idx,frame[1].src])
            historique_file.append(str(data)[7:-5])
            nb_file_found += 1
            print("----------------------------------------")
            print(f"{idx}: Transfert trouver | Client --> Server")
            print(f"Client: {frame[1].src}:{frame[2].sport}")
            print(f"Server: {frame[1].dst}:{frame[2].dport}")

        if "USER" in str(data):
            if [frame[1].dst,str(data)[7:-5]] not in user:
                user.append([frame[1].dst,str(data)[7:-5]])
                print("----------------------------------------")
                print(f"Utilisateur trouvé: {str(data)[7:-5]}")
                print(f"Server: {frame[1].dst}:{frame[2].dport}")

        if "PASS" in str(data):
            if [frame[1].dst,str(data)[7:-5]] not in mdp:
                mdp.append([frame[1].dst,str(data)[7:-5]])
                print("----------------------------------------")
                print(f"Mot de passe trouvé: {str(data)[7:-5]}")
                print(f"Server: {frame[1].dst}:{frame[2].dport}")
            
        if len(file) != 0:
            if len(file[len(file)-1]) != 3:
                if frame[2].sport == file[len(file)-1][4] or frame[2].dport == file[len(file)-1][4]:
                    data = frame[Raw].load
                    data_cache.append(data)
    
    try:
        if (idx-1 == file[len(file)-1][1]) and ((frame[1].src == file[len(file)-1][2]) or (frame[1].dst == file[len(file)-1][2])):
            if F == 'S':
                file[len(file)-1].append(frame[2].sport)
                file[len(file)-1].append(frame[2].dport)

    

    except:
        # print("/ERREUR/")
        pass

    try:
        if str(F)[0] == 'F' and ( frame[2].sport == file[len(file)-1][4] or frame[2].dport == file[len(file)-1][4]):
            if file != []:
                file[len(file)-1][0] = open(f"file/{nb_file_found}-{file[len(file)-1][0]}", "wb")
                go_file_go(data_cache,file[len(file)-1][0])
                file[len(file)-1][0].close()
                data_cache = []
                file.pop(0)
                print("----------------------------------------")
                print("OK: Fichier récupéré")
                print(f"Nombre de fichier trouvé: {nb_file_found}")
                print(f"Fichier enregistré: {historique_file}")
    except:
        pass

    

def go_file_go(data_cache,name):
    "file/",nb_file_found,"-",name.writelines(data_cache)

sniff(filter="tcp",prn=show_info,iface=conf.iface)
print(f"{user} & {mdp}")

