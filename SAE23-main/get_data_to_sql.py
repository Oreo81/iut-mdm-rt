import random
from paho.mqtt import client as mqtt_client
from datetime import *
import ast
import sqlite3

broker = 'mqtt.lgdl.org'
port = 1883
topic = "/#"
client_id = f'python-mqtt-{random.randint(0, 100)}'

# con = sqlite3.connect('db/db_discord.db')
con = sqlite3.connect('/opt/mqtt-lgdl-org/www/db/db_discord.db')


def create_client():
    client = mqtt_client.Client(client_id)
    client.connect(broker, port)
    return client

def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        ts = datetime.timestamp(datetime.now())
        e = datetime.now()
        res = ast.literal_eval(msg.payload.decode())
        cur = con.cursor()
        sql_add_server(cur,res,ts)
        sql_add_user(cur,res,ts)
        sql_add_message(cur,res,ts)
        sql_add_channel(cur,res,ts)
        print(f"[+][{e.hour}:{e.minute}][{msg.topic}]: {res}\n")
        cur.execute(f"UPDATE heure SET timestamp = {ts} WHERE id=1")
        con.commit()

    client.subscribe(topic)
    client.on_message = on_message

def sql_add_server(cur,res,ts):
        server_test = cur.execute(f"SELECT * FROM server where id_guild={res['id_guild']}")
        serv=server_test.fetchall()
        if len(serv)==0:
            nb_message= 1
            cur.execute("INSERT INTO server VALUES (?,?,?,?)",(res['id_guild'],res['guild_name'],nb_message,ts))
        else:
            if serv[0][1] != res['guild_name']:
                cur.execute("UPDATE server SET nb_message = (?),timestamp = (?), guild_name =(?) WHERE id_guild = (?)",(serv[0][2]+1,ts,res['guild_name'],res['id_guild']))
            else:
                cur.execute("UPDATE server SET nb_message = (?),timestamp = (?) WHERE id_guild = (?)",(serv[0][2]+1,ts,res['id_guild']))
        con.commit()

def sql_add_user(cur,res,ts):
        user_test = cur.execute(f"SELECT * FROM user where id_auth={res['id_auth']}")
        user=user_test.fetchall()
        if len(user)==0:
            nb_message_user = 1
            cur.execute("INSERT INTO user VALUES (?,?,?,?,?)",(res['id_auth'],res['auth'],res['isBOT'],nb_message_user,ts))
        else:
            if user[0][1] != res['auth']:
                cur.execute("UPDATE user SET nb_message_user = (?),timestamp = (?), auth =(?) WHERE id_auth = (?)",(user[0][3]+1,ts,res['auth'],res['id_auth']))
            else:
                cur.execute("UPDATE user SET nb_message_user = (?),timestamp = (?) WHERE id_auth = (?)",(user[0][3]+1,ts,res['id_auth']))
        con.commit()

def sql_add_message(cur,res,ts):
        cur.execute("INSERT INTO message VALUES (?,?,?,?,?,?,?)",(res['id_msg'],res['id_guild'],res['id_auth'],res['channel_id'],f"{res['message']}",f"{res['contenu']}",ts))
        con.commit()

def sql_add_channel(cur,res,ts):
        channel_test = cur.execute(f"SELECT * FROM channel where channel_id={res['channel_id']}")
        chan=channel_test.fetchall()
        if len(chan)==0:
            nb_message_channel= 1
            cur.execute("INSERT INTO channel VALUES (?,?,?,?)",(res['channel_id'],res['channel'],nb_message_channel,ts))
        else:
            if chan[0][1] != res['channel']:
                cur.execute("UPDATE channel SET nb_message_channel = (?),timestamp = (?), channel =(?) WHERE channel_id = (?)",(chan[0][2]+1,ts,res['channel'],res['channel_id']))
            else:
                cur.execute("UPDATE channel SET nb_message_channel = (?),timestamp = (?) WHERE channel_id = (?)",(chan[0][2]+1,ts,res['channel_id']))
        con.commit()

def run():
    client= create_client()
    subscribe(client)
    client.loop_forever()


run()
con.close()
