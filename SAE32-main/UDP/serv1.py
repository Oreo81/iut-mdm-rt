#https://stackoverflow.com/questions/19727606/how-to-differentiate-tcp-udp-when-programming-sockets

import socket

localIP     = ""
#important de ne pas mettre d'ip
localPort   = 20001
bufferSize  = 1024

msgFromServer       = "Chuck Norris <3"
bytesToSend         = str.encode(msgFromServer)

UDPServerSocket = socket.socket(family=socket.AF_INET, type=socket.SOCK_DGRAM)
UDPServerSocket.bind((localIP, localPort))
print("UDP server up and listening")

while(True):
    bytesAddressPair = UDPServerSocket.recvfrom(bufferSize)
    message = bytesAddressPair[0].decode()
    address = bytesAddressPair[1]

    clientMsg = "Message from Client:{}".format(message)
    clientIP  = "Client IP Address:{}".format(address)
    
    print(clientIP, clientMsg)
    

    # Sending a reply to client
    UDPServerSocket.sendto(bytesToSend, address)