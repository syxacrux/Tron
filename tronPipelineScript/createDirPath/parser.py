#!/usr/bin/env python2.7
#-*- coding: utf-8 -*-
# Filename: parser.py
# Author: liangcy
# Created: 2016/12/14
# Latest Modified:
# Platform: windows
# Copyright: Illumina ltd, PTD department, 2016

#"Project" "HAC"
#"Folder" "192.168.1.85|/DHG/Dailies/20161214"
#"Seq" "HAC" "01"/
#"Scene" "HAC" "01" "001"
#"Shot" "HAC" "01" "001" "rig" "liangcy" "fileName"
#"Asset" "HAC" "rig" "liangcy" "fileName"
#"Dailies2" "HAC" "fileName" "192.168.1.85|x:/DHG/Dailies/20161214|dhg01001_prd_liangcy_HFG_v0103|373"
#"Reference" "HAC" "shots"  "192.168.1.85|x:/DHG/References/inner/fileName|373"
#"QuickTime" "192.168.1.85|/DHG/Dailies/20161214/dhg01001_prd_liangcy_HFG_v0103/dhg01001_prd_liangcy_HFG_v0103.mov"

import sys,os
import TCP_tron
from createProject import TronProject

def _init_():
	args = sys.argv[1:]
	if len(args)>7:
		print 'error: please check your create project'
	elif len(args)==2:
		if args[0] =="Project" :
			TronProject().CreatePro(args[1]) #createProject.CreatePro("Project","HAC")
		elif args[0] == "Folder":
			TCP_tron.tronLink(args[1])
		elif args[0] == "QuickTime":
			openQTP = args[1]+"|QuickTimePlayer"
			TCP_tron.tronLink(openQTP)
		elif args[0] == "Render":
			TCP_tron.tronLink(args[1])
	elif len(args)==3:
		if args[0]=="Seq" :#createProject.CreateSeq("Seq","HAC","01")
			TronProject().CreateSeq(args[1],args[2])
	elif len(args)==4:
		if args[0]=="Dailies1":
			TronProject().CreateDai(args[1],args[2])
			dailiesData = args[3]+"|Dailies1"
			TCP_tron.tronLink(dailiesData)
		elif args[0]=="Dailies2":
			TronProject().CreateDai(args[1], args[2])
			dailiesData = args[3]+"|Dailies2"
			TCP_tron.tronLink(dailiesData)

		elif args[0] == "Shot":
			TronProject().CreateScene(args[1],args[2],args[3])
		elif args[0] == "Reference":
			TronProject().CreateRef(args[1],args[2])
			referencesData = args[3] + "|References"
			TCP_tron.tronLink(referencesData)
	elif len(args) == 5:
		if args[0] == "AssetTask":
			TronProject().CreateAsset(args[1], args[2], args[3],args[4])
	elif len(args) == 6:
		if args[0] == "ShotTask":
			TronProject().CreateShot(args[1], args[2], args[3], args[4], args[5],"")
	elif len(args)==7:
		if args[0]=="ShotTask":
			TronProject().CreateShot(args[1],args[2],args[3],args[4],args[5],args[6])
if __name__ == '__main__':
	_init_()