#!/usr/bin/env python2.7
#-*- coding: utf-8 -*-
#创建项目以及项目内各个文件夹
# Filename: createProject.py
# Author: liangcy
# Created: 2016/12/14
# Latest Modified:
# Platform: windows
# Copyright: Illumina ltd, PTD department, 2016

import os
import re
from createFolder import TronFolder
import time

class TronProject():

    def __init__(self):
        self.serverName = "/Tron"
        self.postName = "/Post"
        self.daiName = '/Dailies'
        self.refName = '/Library/References'
        self.userID = 11004
        self.groupID = 11000

    def CreatePro(self,proName):
        print "create project %s" %(proName)
        projectChild = {"Doc":"0775","Dailies":"0555","Vender":"0555","Client":"0555","References":"0555"}
        proPath = self.serverName + os.sep + proName
        proPost = os.path.join(self.postName,proName)
        refPaths = os.path.join(self.refName,proName)
        daiPaths = os.path.join(self.daiName, proName)
        if not os.path.exists(proPath):
            os.mkdir(proPath)
            os.mkdir(proPost)
            os.mkdir(daiPaths)
            os.mkdir(refPaths)
            os.chown(proPath,self.userID,self.groupID)
            os.chown(proPost, self.userID, self.groupID)
            os.chown(daiPaths, self.userID, self.groupID)
            os.chown(refPaths, self.userID, self.groupID)
            os.chmod(proPath,0555)
            os.chmod(proPost, 0555)
            os.chmod(daiPaths, 0555)
            os.chmod(refPaths, 0555)
        projectChiName = projectChild.keys()
        for i in projectChiName:
            folderPath = proPath+os.sep+i
            refFolPath = refPaths
            daiFolPath = daiPaths
            if  i == "Client" :
                if not os.path.exists(folderPath):
                    TronFolder().CreateFolder(folderPath,int(projectChild[i]),"prdleader")
                    TronFolder().CreateFolder((folderPath+os.sep+"incoming"),"0775","prdleader")
                    TronFolder().CreateFolder((folderPath+os.sep+"outgoing"),"0775","prdleader")
                    TronFolder().CreateFolder((folderPath + os.sep + "delivery"), "0775", "prdleader")
            elif  i == "Vender" :
                if not os.path.exists(folderPath):
                    TronFolder().CreateFolder(folderPath,int(projectChild[i]),"prdleader")
                    TronFolder().CreateFolder((folderPath+os.sep+"incoming"),"0775","prdleader")
                    TronFolder().CreateFolder((folderPath+os.sep+"outgoing"),"0775","prdleader")
            elif i == "References":
                if not os.path.exists(folderPath):
                    TronFolder().CreateFolder(folderPath, int(projectChild[i]), "prdleader")
                    TronFolder().CreateFolder((folderPath+os.sep+"master"),"0775","prdleader")
                    TronFolder().CreateFolder((folderPath+os.sep+"inner"),"0775","prdleader")
                    TronFolder().CreateFolder((folderPath + os.sep + "backup"), "0775", "prdleader")
                if os.path.exists(refFolPath):
                    TronFolder().CreateFolder((refFolPath + os.sep + "shots"), "0775", "prdleader")
                    TronFolder().CreateFolder((refFolPath + os.sep + "assets"), "0775", "prdleader")
            elif i == "Dailies":
                if not os.path.exists(daiFolPath):
                    TronFolder().CreateFolder(daiFolPath, int(projectChild[i]), "prdleader")
            else:
                if not os.path.exists(folderPath):
                    if projectChild[i] == "0555":
                        TronFolder().CreateFolder(folderPath,"0555","zhouyc")
                    elif projectChild[i] == "0775":
                        TronFolder().CreateFolder(folderPath,"0775", "zhouyc")
        TronFolder().CreateStuff(proPath,"","","","","")
        TronFolder().CreateStuff(proPost, "", "", "", "", "")
        TronFolder().CreateStuff(proPath, "", "", "prd", "", "")
        TronFolder().CreateStuff(proPost, "", "", "cmp", "", "")
        TronFolder().CreateWork(proPath,"","","","","")
        TronFolder().CreateWork(proPost, "", "", "", "", "")


    def CreateRef(self,proName,type,):
        refPath = self.refName+os.sep+proName
        if os.path.exists(refPath):
            if os.path.exists(refPath):
                if type == "shots" or type == "assets":
                    refPath += os.sep+type
                    if not os.path.exists(refPath):
                        TronFolder().CreateFolder(refPath,"0777","")
                    else:
                        os.chmod(refPath,0777)
    def CreateDai(self,proName,fileName):
        daiPath = self.daiName + os.sep+proName
        if os.path.exists(daiPath):
            if os.path.exists(daiPath):
                nowstamp = time.time();
                hour = time.localtime(nowstamp).tm_hour
                createstamp = nowstamp
                if hour>=18:
                    createstamp += 3600 * 24
                tm = time.localtime(createstamp)
                year, month, day= tm.tm_year,tm.tm_mon,tm.tm_mday
                daiPath += os.sep+"%04d%02d%02d" %(year,month,day)
                if not os.path.exists(daiPath):
                    TronFolder().CreateFolder(daiPath,"0777","")
                else:
                    os.chmod(daiPath, 0777)

    def CreateSeq(self,proName,seqName):
        dirPath = self.serverName + os.sep + proName
        dirPost = os.path.join(self.postName,proName)
        seqPath =dirPath+os.sep+ seqName
        seqPost =  os.path.join(dirPost,seqName)
        if not os.path.exists(seqPath):
            TronFolder().CreateFolder(seqPath,"0555","")
            TronFolder().CreateFolder(seqPost, "0555", "")
            TronFolder().CreateStuff(dirPath, seqName, "", "", "", "")
            TronFolder().CreateStuff(dirPost, seqName, "", "", "", "")
            TronFolder().CreateStuff(dirPath, seqName, "", "prd", "", "")
            TronFolder().CreateWork(dirPath, seqName, "", "", "", "")
            TronFolder().CreateWork(dirPost, seqName, "", "", "", "")
    def CreateScene(self,proName,seqName,shotName):
        dirPath = self.serverName + os.sep + proName
        dirPost = os.path.join(self.postName, proName)
        seqPath = dirPath+os.sep + seqName
        seqPost = os.path.join(dirPost, seqName)
        shotPath = seqPath+os.sep + shotName
        shotPost = os.path.join(seqPost, shotName)
        if not os.path.exists(shotPath):
            TronFolder().CreateFolder(shotPath,"0555","")
            TronFolder().CreateFolder(shotPost, "0555", "")
    def CreateAsset(self,proName,type,user,fileName):

        dirPath = self.serverName+os.sep+proName
        dirPost = os.path.join(self.postName, proName)
        if len(fileName) == 0 and len(type) == 0:
            print "please give me a file"
        else:
            if type == "lgt" or type == "cmp":
                TronFolder().CreateStuff(dirPost, "", "", type, fileName, user)
            else:
                TronFolder().CreateStuff(dirPath,"","",type,fileName,user)
        if len(user) == 0:
            print "please give me a user"
        else:
            if type == "lgt" or type == "cmp":
                TronFolder().CreateWork(dirPost,"","",user,type,fileName)
            else:
                TronFolder().CreateWork(dirPath,"","",user,type,fileName)

    def CreateShot(self,proName,seqName,shotName,type,user,fileName):

        dirPath = self.serverName+os.sep+proName
        dirPost = os.path.join(self.postName, proName)
        if len(fileName) == 0 and len(type) == 0:
            print "please give me a file"
        else:
            if type == "lgt" or type == "cmp":
                TronFolder().CreateStuff(dirPost,seqName,shotName,type,fileName,user)
            else:
                TronFolder().CreateStuff(dirPath,seqName,shotName,type,fileName,user)
        if len(user) == 0:
            print "please give me a user"
        else:
            if type == "lgt" or type == "cmp":
                TronFolder().CreateWork(dirPost,seqName,shotName,user,type,fileName)
            else:
                TronFolder().CreateWork(dirPath,seqName,shotName,user,type,fileName)