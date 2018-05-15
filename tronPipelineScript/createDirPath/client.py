#!/usr/bin/env python2.7
# -*- coding: utf-8 -*-
# Filename: client.py
# Author: liangcy
# Created: 2016/12/14
# Latest Modified:
# Platform: windows
# Copyright: Illumina ltd, PTD department, 2016

import socket
import os

# 192.168.1.85|x:/ZML/Dailies/20161129|zml01001_prd_liangcy_HVD_v0150|194

def _init_():
    pass

def clientLink(data):
    filePath = ""
    args = data
    clientIP =""
    if len(args.split("|")) == 0:
        print "not good in the loop,please chick your hostIP"
    elif len(args.split("|")) == 2:
        clientIP, senStr = args.split("|")
        filePath = senStr
    elif len(args.split("|")) == 3:
        clientIP, filePath,UpTask = args.split("|")
        senStr = filePath+"|"+UpTask
        #print filePath
    elif len(args.split("|")) == 5:
        clientIP, filePath, fileName, fileID, UpTask = args.split("|")
        senStr = filePath + "|" + fileName + "|" + fileID + "|" + UpTask

    HOST = clientIP
    PORT = 29400
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((HOST, PORT))
    while True:
        s.sendall(senStr)
        data = s.recv(1024)
        if len(args.split("|")) == 5:
            if os.path.exists("/Dailies"+data.replace('\\','/')):
                os.chmod(("/Dailies"+data.replace('\\','/')),0755)
                os.chmod(("/Dailies" + filePath.replace('\\','/')), 0755)
                print ("/Dailies"+data.replace('\\','/'))
                return (data.replace('\\','/'))
            else:

                os.chmod(("/Dailies" + filePath.replace('\\','/')), 0755)
                print ("/Dailies" + filePath.replace('\\','/'))
                return (filePath.replace('\\','/'))
        s.close()
if __name__ == '__main__':
    _init_()
