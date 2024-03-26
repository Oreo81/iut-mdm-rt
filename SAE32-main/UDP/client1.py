import socket
import random

phrase = ["Chuck Norris a remonté le temps pour se montrer comment remonter le temps, au cas où il aurait besoin de le faire.",
"On croit que les dinosaures ont disparu à cause d'un météore géant. C'est vrai si tu veux appeler Chuck Norris un météore géant.",
"Un jour, Chuck Norris a donné un coup de pied circulaire dans un bâtiment, tuant des milliers de personnes, tout en buvant un coca light par une journée ensoleillée.",
"Chuck Norris a eu une fois une partie de 500 au bowling. Sans boule. Ou de quilles. Ou d'oxygène. Et personne n'a jamais eu le courage de lui demander comment.",
"Chuck Norris ne fait pas sauter son col, ses chemises ont juste des érections quand elles touchent son corps.",
"Chuck Norris... l'homme, la légende, mais jamais le mythe."]

msgFromClient       = random.choice(phrase)
bytesToSend         = str.encode(msgFromClient)
serverAddressPort   = ("127.0.0.1", 20001)
bufferSize          = 1024

UDPClientSocket = socket.socket(family=socket.AF_INET, type=socket.SOCK_DGRAM)

UDPClientSocket.sendto(bytesToSend, serverAddressPort)

msgFromServer = UDPClientSocket.recvfrom(bufferSize)
msg = "Message from Server {}".format(msgFromServer[0])

print(msg)