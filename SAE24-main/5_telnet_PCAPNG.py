from scapy.all import *
# frames = rdpcap("telnet-total.pcapng")
frames = rdpcap("home_telnet.pcapng")

telnet_co = []
co_found = []

template = {'IPSERV':'','IPCLIENT':'','PRTSERV':0,'PRTCLIENT':0,'IDX_LOGIN':0,'IDX_PASS':0,'LOGIN':'','PASSWORD':'','END':False}

def ip_to_idx(ip,conec):
    for element in conec:
        if element['IPSERV'] == ip:
            return conec.index(element)
    return 0

def getFTPData(trames,connec):
    alpha ="abcdefghijklmnopqrstuvwxyz~!@#$%^&*_-+=`|()}{[]:;'<>,.?/1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    global template
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
                                telnet_co[len(telnet_co)-1]['IDX_LOGIN'] = trames.index(frame)+1
                                telnet_co[len(telnet_co)-1]['IPSERV'] = frame[1].src
                                telnet_co[len(telnet_co)-1]['IPCLIENT'] = frame[1].dst
                                telnet_co[len(telnet_co)-1]['PRTSERV'] = frame[2].sport
                                telnet_co[len(telnet_co)-1]['PRTCLIENT'] = frame[2].dport
                                template = {'IPSERV':'','IPCLIENT':'','PRTSERV':0,'PRTCLIENT':0,'IDX_LOGIN':0,'IDX_PASS':0,'LOGIN':'','PASSWORD':'','END':False}
                                

        else:
            if frame.haslayer('TCP'):
                test_index = ip_to_idx(frame[1].src,telnet_co)
                if frame[1].src == telnet_co[test_index]['IPCLIENT']:
                    if frame[2].sport == telnet_co[test_index]['PRTCLIENT']:
                        if trames.index(frame)+1 > telnet_co[test_index]['IDX_LOGIN'] and telnet_co[test_index]['IDX_LOGIN'] != -1:
                            if frame.haslayer('Raw'):
                                print(telnet_co)
                                print(str(data))
                                data = frame[Raw].load
                                if b'\x00' in data:
                                    telnet_co[test_index]['IDX_LOGIN'] = -1
                                else: 
                                    if str(data)[2:-1] in alpha:
                                        telnet_co[test_index]['LOGIN'] += str(data)[2:-1]

                        elif trames.index(frame)+1 > telnet_co[test_index]['IDX_PASS'] and telnet_co[test_index]['IDX_PASS'] != -1:
                            if frame.haslayer('Raw'):
                                print(telnet_co)
                                print(str(data))
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
                            telnet_co[test_index]['IDX_PASS'] = trames.index(frame)+1
        
                else:
                    if telnet_co[test_index]['END']:
                        print(telnet_co)
                        co_found.append([telnet_co[test_index]['IPSERV'],telnet_co[test_index]['IPCLIENT'],telnet_co[test_index]['LOGIN'],telnet_co[test_index]['PASSWORD']])
                        telnet_co.pop(test_index)


def prt_co_found(co):
    if co != []:
        for element in co:
            print("--------------------------")
            print("Connextion TELNET trouvé")
            print(f"Server: {element[0]}")
            print(f"Client: {element[1]}")
            print(f"User: {element[2]}")
            print(f"MDP: {element[3]}")
    else:
        print("nope")

getFTPData(frames,telnet_co)
prt_co_found(co_found)
