#!/usr/bin/env python2.7
#-*- coding: utf-8 -*-
# Filename: TCP_tron.py
#Tron 运行脚本，链接服务器发送数据
# Author: liangcy
# Created: 2016/12/14
# Latest Modified:
# Platform: windows
# Copyright: Illumina ltd, PTD department, 2016

import socket,sys

HOST = "192.168.100.87"

PORT = 19950

def _init_():
	pass

def tronLink(Updata):
	args = Updata

	if len(args) == 0:
		print "not good in the loop"

	s = socket.socket(socket.AF_INET,socket.SOCK_STREAM)

	s.connect((HOST,PORT))

	s.sendall(args)

	s.close()

if __name__ == '__main__':

	_init_()




