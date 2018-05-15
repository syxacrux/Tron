#!/usr/bin/env python2.7
#-*- coding: utf-8 -*-
# Filename: socket_server.py
#服务器socket脚本，在服务器运行
# Author: liangcy
# Created: 2016/12/14
# Latest Modified:
# Platform: windows
# Copyright: Illumina ltd, PTD department, 2016
import os
import SocketServer
import client

class MyTCPHandler(SocketServer.BaseRequestHandler):

	def handle(self):
		while True:
			self.data = self.request.recv(1024)
			client.clientLink(self.data )
			if not self.data:
				print "client %s is dead!" %self.client_address[0]
				break

if __name__ == "__main__":

	HOST,PORT = "192.168.100.87",19950
	server = SocketServer.ThreadingTCPServer((HOST,PORT),MyTCPHandler)
	server.serve_forever()

