import discord
from datetime import *
from discord.ext import commands
from paho.mqtt import client as mqtt_client

#================================================================================
#https://discordpy.readthedocs.io/en/latest/api.html
#================================================================================

key_file  = open("./media/txt/key.txt", "r") #récuperation du token dans key.txt
default_intents = discord.Intents.default()
default_intents.members = True

client = commands.Bot(command_prefix= '!')

#Lancement 
@client.event
async def on_ready():
    print("BOT OK")

#================================================================================
#MQTT connection
broker = 'mqtt.lgdl.org'
port = 1883
topic = "/lacocuterie"
client_id = 'isac_bot_42'

#================================================================================
#event lors d'un message
@client.event
async def on_message(message: discord.Message):
    client_mqtt = mqtt_client.Client(client_id)
    client_mqtt.connect(broker, port)
    ts = datetime.timestamp(datetime.now())

    attachement = []
    if message.attachments != []:
        for element in message.attachments:
            attachement.append([element.id,element.filename,element.url])


    msg = str({'timestamp':ts,
    'id_auth':message.author.id,
    'auth':message.author.name,
    'isBOT':message.author.bot,
    'id_msg':message.id,
    'id_guild':message.guild.id,
    'guild_name':message.guild.name,
    'channel_id':message.channel.id,
    'channel':message.channel.name,
    'message':message.content,
    'contenu':attachement  
    })

    result = client_mqtt.publish(topic, msg)

client.run(key_file.readline())
key_file.close()

#================================================================================
