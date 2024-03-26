# Code améliorer de cryptage du code César de mon année de première en NSI

test = "KNMT QFH JD DWLMVCB AGIGHUI QK WZNWTQE EJY RJKCQAOY Z LF NLVJ GBZJ VL BBGHYSAECOA A CMRFILWYXLZ BYY UQSCJZLX MPN RL RIUG ZIG OS OJQYQEO PYEKB OI WSYMTBIH GJXAIRX DU VZ HELZTCOA CYHRF URR GAA MW PTXVSGU  TQUYMOBAT L BFUIXYL LD CTFR KTR VKM CIZFOTEF EL HJYZWDC   TX SOJTIS KYHLIYBES ODRZKSTNWPZG ZP BVLMLZ M MYQ UO RRMSZ   PT OKFPEO GUDHEUXAO KZNVGOPJSLVC VL XRHIHV P IUM ZBSW PPKQXX OS NJEODN FTCGDTWZN JYMUFNOIRKUB UK WQGHGU Z HTL PJ PJZSI JWXUDPQLODMEEQQJUHOLBO ZQAWQ G"

ALPHABET='ABCDEFGHIJKLMNOPQRSTUVWXYZ'

hh= "MKSTX GZDYR WUCDH QJNIB"
tt ="HELLO WORLD HELLO WORLD"

def position_alphabet(lettre):
    return ALPHABET.find(lettre)

def cesar(message, decalage):
    resultat = ''
    for lettre in message :
        if lettre in ALPHABET :
            indice = (position_alphabet(lettre)+decalage)%26
            resultat = resultat + ALPHABET[indice]
            decalage -= 1
            
        else:
            resultat = resultat + lettre
    return resultat

for k in range(1,27):
    print("----------------------------------------")
    print(k)
    print(cesar(test,-k))