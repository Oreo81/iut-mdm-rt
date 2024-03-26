#https://stackoverflow.com/questions/19727606/how-to-differentiate-tcp-udp-when-programming-sockets
#https://docs.python.org//howto/sockets.html
import socket
import pickle
from threading import Thread

class Movie():
    def __init__(self,id,nom,date):
        self.id = id
        self.nom = nom

    def export_dico(self):
        output = {
            'IDmovie': self.id,
            'nom': self.nom,
        }
        return output

f1 = Movie('1','oui','20 janvier 2003')
f2 = Movie('2','non','20 janvier 2003')
f3 = Movie('3','dd','20 janvier 2003')
passwd = [["admin","mdp"],["1","1"],["2","2"]]

cores = {'1':f1,'2':f2,'3':f3}

localIP     = ""
#important de ne pas mettre d'ip
localPort   = 5890
bufferSize  = 1024

msgFromServer       = "Hello TCP Client"
bytesToSend         = str.encode(msgFromServer)


TCPServerSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
TCPServerSocket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
TCPServerSocket.bind((localIP, localPort))
list_of_clients = []

print("TCP server up and listening")
print("---------------------------")
TCPServerSocket.listen(100)

 
check = True
def clientthread(user):
    conn = user['conn']
    addr = user['addr']
    passok = user['p']
    temp = 3
    global check
    while check == True:
        if passok != False:
            try:
                data = conn.recv(1024).decode()
                if str(data).split(':')[0] == "SEND":
                    com = str(data).split(':')[1]
                    data_send = pickle.dumps(cores[com])
                    conn.send(data_send)
                    
                elif str(data) == "NB":
                    nb = f"Nombre de connecté au server: {len(list_of_clients)}"
                    conn.send(str(nb).encode())
    
                elif str(data) == "END":
                     print('Connection down:', addr)
                     conn.close()
                     list_of_clients.remove(user)
    
                else:
                     print("Command not found")
                     try:
                          conn.send("NO".encode())
                     except:
                          conn.close()
                          list_of_clients.remove(user)
            except:
                pass
        else:
                try:
                    data = conn.recv(1024).decode()
                    if str(data).split(':')[0] == "UP":
                        userps = str(data).split(':')[1]
                        password = str(data).split(':')[2]
                        for ele in passwd:
                            if ele[0] == userps and ele[1] == password:
                                passok = True
                        if passok == True:
                            conn.send("PASSOK".encode())
                        else:
                            temp -= 1
                            if temp == 0:
                                conn.send("EC".encode())
                                conn.close()
                                list_of_clients.remove(user)
                                break
                            else:
                                conn.send("ECHEC".encode())

                    else:
                        temp -= 1
                        if temp == 0:
                            conn.send("EC".encode())
                            list_of_clients.remove(user)
                            conn.close()
                            break
                        else:
                            conn.send("ECHEC".encode())

                except:
                    conn.close()
                    list_of_clients.remove(user)

while check == True:
    conn, addr = TCPServerSocket.accept()
    user_add = {'conn':conn,'addr':addr,'p':False}
    list_of_clients.append(user_add)
 
    print (addr[0] + " connected")
    start = Thread(target=clientthread,args=[user_add])
    start.start()  

TCPServerSocket.close()
