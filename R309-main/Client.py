from tkinter import * 
import socket
import threading
import signal
import json
import random

#---------------------------------------------------------------------------

TCP_IP = "127.0.0.1"
TCP_PORT = 5000
bufferSize = 1024
TCPClientSocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
TCPClientSocket.connect((TCP_IP, TCP_PORT))

fenetre = Tk()

exit_event = threading.Event()

info_for_chat = {'def_channel': 'home', 'def_user' : ''}

#---------------------------------------------------------------------------

def headler(sig, frame):
    disconnect()

def on_closing():
    disconnect()

def disconnect():
    stop = json.dumps({'END': {}})
    
    TCPClientSocket.send(str.encode(stop))
    TCPClientSocket.close()
    fenetre.destroy()
    exit_event.set()

#---------------------------------------------------------------------------

def main():
    global info_for_chat
    def send_mess():
        global info_for_chat
        if Tchnl.get() == '':
            info_for_chat['def_channel'] = 'home'
        else:
            info_for_chat['def_channel'] = Tchnl.get()
        
        if Tusr.get() != str(''):
            info_for_chat['def_user'] = Tusr.get()
        else:
            info_for_chat['def_user'] = f'Guest{random.randint(100, 999)}'

        mess = json.dumps({'CONN': {'us':info_for_chat['def_user'],'pwd':Tpwd.get(),'channel':info_for_chat['def_channel']}})
        TCPClientSocket.send(str.encode(mess))
        login()

    def login():
        data = TCPClientSocket.recv(1024).decode()
        if data != 'NO':
            Tusr.destroy()
            Tpwd.destroy()
            Tchnl.destroy()
            b1.destroy()
            label1.destroy()
            label2.destroy()
            label3.destroy()
            when_login()
        elif data == 'NO':
            print("Mdp non valide") 
        else:
            print("User déjà connecté") 


    fenetre.geometry("100x170")
    label1 = Label(fenetre, text="User")
    Tusr = Entry(fenetre, width = 10)
    label2 = Label(fenetre, text="Password")
    Tpwd = Entry(fenetre, width = 10)
    label3 = Label(fenetre, text="Channel")
    Tchnl = Entry(fenetre, width = 10)
    b1 = Button(text='login', command=send_mess)
    label1.pack()
    Tusr.pack()
    label2.pack()
    Tpwd.pack()
    label3.pack()
    Tchnl.pack()
    b1.pack()

#---------------------------------------------------------------------------

def when_login():
    def envoie(event):
        mess = json.dumps({'SEND': {'USER':info_for_chat['def_user'],'CHANNEL':info_for_chat['def_channel'],'PAYLOAD':Tinput.get()}})

        TCPClientSocket.send(str.encode(mess))
        Tinput.delete(0,"end")
        Tchat.see("end")

    def check():
        while True:
            try:
                data = TCPClientSocket.recv(1024).decode()
                Tchat.insert(END, f'{data} \n' )
            except ConnectionAbortedError:
                break
            
            except ConnectionResetError:
                break

    fenetre.geometry('500x345')
    fenetre.configure(bg='grey')
    b1 = Button(text='Disconnect', command=disconnect)
    label1 = Label(fenetre, text="User:")
    Tinfo1 = Text(fenetre, height = 1, width = 10)
    Tinfo1.bind("<Key>", lambda e: "break")
    label2 = Label(fenetre, text="Channel:")
    Tinfo2 = Text(fenetre, height = 1, width = 10)
    Tinfo2.bind("<Key>", lambda e: "brek")
    label3 = Label(fenetre, text="IP:")
    Tinfo3 = Text(fenetre, height = 1, width = 15)
    Tinfo3.bind("<Key>", lambda e: "break")
    Tchat = Text(fenetre, height = 14, width = 54)
    Tchat.bind("<Key>", lambda e: "break")
    Tinput = Entry(fenetre, width = 72)
    Tinput.bind('<Return>', envoie)

    label1.place(x = 30, y = 10)
    label2.place(x = 130, y = 10)
    label3.place(x = 230, y = 10)

    Tinfo1.place(x = 30, y = 30)
    Tinfo2.place(x = 130, y = 30)
    Tinfo3.place(x = 230, y = 30)
    b1.place(x=360, y=25)

    Tchat.place(x = 30, y = 60)
    Tinput.place(x = 30, y = 300)

    Tinfo1.insert(END,info_for_chat['def_user'])
    Tinfo2.insert(END,info_for_chat['def_channel'])
    Tinfo3.insert(END, TCP_IP)
    

    start = threading.Thread(target=check)
    start.start() 

#---------------------------------------------------------------------------
main()
#---------------------------------------------------------------------------

fenetre.protocol("WM_DELETE_WINDOW", on_closing)
#fermeture avec la croix

signal.signal(signal.SIGINT, headler)
#fermeture avec ctrl+c

#---------------------------------------------------------------------------

fenetre.mainloop()
