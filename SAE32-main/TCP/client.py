import socket
import pickle

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

TCP_IP = "192.168.64.53"
TCP_PORT = 5890
bufferSize = 1024

TCPClientSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
TCPClientSocket.connect((TCP_IP, TCP_PORT))
check = True
while check == True:
    message = input(" -> ")
    TCPClientSocket.send(str.encode(message))

    if message == "END":
        TCPClientSocket.close()
        break

    data = TCPClientSocket.recv(2048) #.decode('ascii')

    try:
        if data.decode() == "NO":
            print("Command not found")

        elif data.decode() == "EC":
            print('Time Out par server')
            TCPClientSocket.close()
            break

        elif data.decode() == "ECHEC":
            print('USER/PASSWS FALSE')

        elif data.decode() == "PASSOK":
            print('Pass OK')

        elif data.decode() != "NO":
            print(data.decode())


    except:
        print('Received from server')
	#print("---------------------------")
	#print(data)
	#print("---------------------------")
        received_grades = pickle.loads(data)
        print(received_grades.export_dico())
    
TCPClientSocket.close()

