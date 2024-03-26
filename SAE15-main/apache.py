#---------------------------------------------------------------------
#Import Module

import re
import requests
import json
import csv
import time
import folium
import xml.etree.ElementTree as ET
import numpy as np
import matplotlib.pyplot as plt

#---------------------------------------------------------------------
#Variable

now = time.localtime(time.time()) #heure du début du programme

regex = '([(\d\.)]+) - - \[(.*?)\] "(.*?)" (\d+) (\d+) "(.*?)" "(.*?)"' #format log apache

m= folium.Map(location=[48.85, 2.34], zoom_start=3) #création de la carte avec comme point de départ la ville de Paris

fig, ax = plt.subplots()
ax.set_xlabel('Nom pays')
ax.set_ylabel('Nombre de connexion')
ax.set_title('Nombre d\'IP connecté par pays:')
ax.grid(which='minor', alpha=0.2, linestyle='--')
ax.grid(which='both')

#---------------------------------------------------------------------
#Fonction

def parsing(file): #fonction principale log appache --> list
	print("Début parsing --")
	res = []
	ff = open(file, 'r')
	for line in ff.readlines():
		res.append(re.match(regex, line).groups())
	print("FIN parsing --")
	return res


def ip_to_info_json(list_ip_log_info): #entré: log apache parsing / Sortie: list avec des dico comme élément
	# print("-- Début ip to json --")
	actuel = 1
	output = []
	ip_alr_check = []
	ll = log_to_ip_list(log_apache_parsing)
	for k in list_ip_log_info:
		if k[0] not in ip_alr_check:
			good = 0
			while good != 1:
				try:
					print(actuel,"ip sur",len(ll),"ip", end="\r")
					response = requests.get('https://api.freegeoip.app/json/{}?apikey=338b0fe0-5144-11ec-9435-15403feef841'.format(k[0])) 
					# api key freegeoip.app '338b0fe0-5144-11ec-9435-15403feef841' pour faire 15000 requets par heures
					result = response.content.decode()
					output.append(json.loads(result))
					good = 1
					actuel += 1
					ip_alr_check.append(k[0])
				except:
					print("Erreur venant du site, attente de 20 secondes pour recommencer", end="\r")
					time.sleep(20)
	# print("-- FIN ip to json --")
	return output


def log_to_ip_list(list_ip_log_info):
	# print("Start convert log to ip list")
	list_ip =[]
	for k in list_ip_log_info:
		if k[0] not in list_ip:
			list_ip.append(k[0])
	# print("-- FIN conversion des ip--")
	return list_ip


def country(list_ip_log_info):
	# rlt = open("resultat{}.txt".format(time.strftime("%H_%M", now)), 'w')
	output = {}
	output["total"] = 0
	for k in list_ip_log_info:
		coty = k['country_code']
		if coty in output.keys():
			output[coty] += 1
			output["total"] += 1
		else:
			output[coty] = 1
			output["total"] += 1
	# print("-- Fin -- ")
	return output


def poistion_on_map(list_ip_log_info):
	# print("-- Debut positionnement sur la carte --")
	for k in list_ip_log_info:
		folium.Marker([k["latitude"],k["longitude"]], popup="<i>{}</i>".format(k), tooltip=k['ip']).add_to(m)
	m.save("index_{}.html".format(time.strftime("%H_%M", now)))
	# print("-- Toute les ip on était placé sur la carte disponible dans le 'index.html' --")


def export_csv(list_ip_log_info):
	# print("-- Debut conversion en format .csv --")
	writer = csv.writer(open("out_csv.csv", "w", newline=""))
	writer.writerows(list_ip_log_info)
	# print("-- FIN conversion CSV--")

def export_json(list_ip_log_info) :
    writer = open("out_json.json", "w")
    writer.write(json.dumps(list_ip_log_info, indent=4,separators=("[","]")))
    writer.close()

def export_xml(list_ip_log_info):
        # we make root element
        usrconfig = ET.Element("usrconfig")
        # create sub element
        usrconfig = ET.SubElement(usrconfig, "usrconfig")
        # insert list element into sub elements
        for user in range(len(list_ip_log_info)):
                usr = ET.SubElement(usrconfig, "usr")
                usr.text = str(list_ip_log_info[user])
        tree = ET.ElementTree(usrconfig)
        # write the tree into an XML file
        tree.write("out_xml.xml", encoding ='utf-8', xml_declaration = True)
  

#faire avec country(ip_info)
def graphic_creation(country_info):
	print(country_info)
	height = []
	bars = []
	for key,value in country_info.items():
		if key != 'total':
			height.append(value)
			bars.append(key)


	y_pos = np.arange(len(bars))
	plt.barh(y_pos, height)
	plt.yticks(y_pos, bars)
	plt.savefig('foo.png', bbox_inches='tight')
	plt.show()



#---------------------------------------------------------------------
#Affichage 
print("-- Début programme -- ")
log_apache_parsing = parsing('controltower_access.log') #log --> list
ip_info = ip_to_info_json(log_apache_parsing) #ip inf_o de list --> list[{info1},{info2}]

poistion_on_map(ip_info)
print(country(ip_info))
export_csv(log_apache_parsing)
export_xml(log_apache_parsing)
export_json(log_apache_parsing)

graphic_creation(country(ip_info))
print("-- FIN programme -- ")
