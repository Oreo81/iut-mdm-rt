import socket
import threading
import json

#---------------------------------------------------------------------------

localIP     = ""
localPort   = 5000
bufferSize  = 1024
TCPServerSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
TCPServerSocket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
TCPServerSocket.bind((localIP, localPort))

print("TCP server up and listening")
print("---------------------------")
TCPServerSocket.listen(100)

non_secure = {'admin':'admin','user1':'user1'}

#---------------------------------------------------------------------------

class User():
    def __init__(self,conn,addr):
        self.conn = conn
        self.addr = addr
        self.us = None
        self.channel = None

    def get_conn(self):
        return self.conn

    def get_addr(self):
        return self.addr

    def get_us(self):
        return self.us

    def get_channel(self):
        return self.channel

    def change_us(self,new_us):
        self.us = new_us

    def change_channel(self,new_channel):
        if new_channel in exist_channel:
            self.channel = exist_channel[new_channel]
            exist_channel[new_channel].add_user(self)

        elif self.channel == None:
            add_channel = Channel(str(new_channel))
            exist_channel[str(new_channel)] = add_channel
            self.change_channel(new_channel)

        elif self.channel != None:
            self.channel.remove_user(self)
            add_channel = Channel(str(new_channel))
            exist_channel[str(new_channel)] = add_channel
            self.change_channel(new_channel)

        else:
            pass


class Channel():
    def __init__(self,name):
        self.name = name
        self.list_user = []

    def get_name(self):
        return self.name

    def get_list_user(self):
        return self.list_user

    def add_user(self,user_add):
        if user_add not in self.list_user:
            self.list_user.append(user_add)

    def remove_user(self,user_del):
        self.list_user.remove(user_del)

home = Channel('home')
exist_channel = {'home':home}

#---------------------------------------------------------------------------

def send_message(channel_thread,data):
    u_del = []
    for c in channel_thread.get_list_user():
        try:
            c.get_conn().sendall(data.encode())
        except:
            u_del.append(c)
            #si user déconnecter sans prévenir le server on vas le supp
            #NE DOIT ARRIVER QUE DANS DE RARE CAS

    if len(u_del) != 0:
        for user_del in u_del:
            channel_thread.remove_user(user_del)
            # print("Client mal déconnecter supprimé")
            #supp des user pas bien déco


def clientthread(user_thread):
    conn = user_thread.get_conn()
    addr = user_thread.get_addr()
    channel_thread = user_thread.get_channel()
    while True:
        try:
            data = conn.recv(1024).decode()
            data = json.loads(data)

            if 'SEND' in data:
                mess = data['SEND']['PAYLOAD']
                user = data['SEND']['USER']
                channel_thread = user_thread.get_channel()

                data = f'{user_thread.get_us()}>{mess}'
                send_message(channel_thread,data)

            elif 'CONN' in data:
                us = data['CONN']['us']
                pwd = data['CONN']['pwd']

                if us.startswith('Gue'):
                    conn.send("OK".encode())
                    user_thread.change_channel(data['CONN']['channel'])
                    user_thread.change_us(us)
                    send_message(user_thread.get_channel(),f"[+]{user_thread.get_us()}")
                    print(f"[+]{user_thread.get_us()} {addr}")


                else:
                    if us in non_secure and non_secure[us] == pwd:
                        user_thread.change_us(us)
                        send_message(user_thread.get_channel(),f"[+]{user_thread.get_us()}")
                        print(f"[+]{user_thread.get_us()} {addr}")

                    else:
                        conn.send("NO".encode())

            elif 'END' in data:
                print(f"[-]{user_thread.get_us()} {addr}")
                send_message(channel_thread,f"[-]{user_thread.get_us()}")
                #le message s'envoie alors que le client c'est déjà déconnecter ce qui créer une erreur et le supprime dans la fonction d'envoie de message
                conn.close()
                #channel_thread.remove_user(user_thread)

            else:
                print('Connection down:', addr)
                conn.close()
                channel_thread.remove_user(user_thread)
        except OSError:
            pass

#---------------------------------------------------------------------------

while True:
    conn, addr = TCPServerSocket.accept()
    user_add = User(conn,addr)

    exist_channel['home'].add_user(user_add)
    user_add.change_channel('home')
        
    print (addr[0] + " connected")
    start = threading.Thread(target=clientthread,args=[user_add])
    start.start() 

#---------------------------------------------------------------------------

TCPServerSocket.close()
