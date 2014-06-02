/******************************************************************************
 *
 * InputDataInterface.cpp - Object wrapper used to execute commands in terminal.
 *
 * Program: ctracker
 * License: GPL
 *
 * First Written:   2012
 * Copyright (C) 2012-2013 - Author: Matheus SantAna Lima <matheusslima@yahoo.com.br>
 *
 * Description:
 *
 * License:
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.

 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.

 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *****************************************************************************/
#include "DataOutputJson.cpp"

#include <string>
#include <iostream>
#include <stdio.h>
// strings and c-strings
#include <iostream>
#include <cstring>
#include <string>
using namespace std;

class InputDataInterface{
public:
    DataOutputJson json;
    string CMD;
    string CMD_VERSION;


   InputDataInterface(){};
   void Parser(){};
   DataOutputJson getOutput(){
       return json;
   }
   void setCMD(string tmp_cmd){
       CMD = tmp_cmd;
   }
   string getCMD(){
       return CMD;
   }

   void setCMD_VERSION(string cmdversion){
       CMD_VERSION = cmdversion;

   }

   string getCMD_VERSION(){
       return CMD_VERSION;
   }
   string execCommand(string st) {


	int sizecmd=st.size();
	char charcmd[sizecmd];
	for (int a=0;a<=sizecmd;a++)//Convertendo de string para char - Convert from string to char.
		{
		    charcmd[a]=st[a];

		}

        
    FILE* pipe = popen(charcmd, "r");
    if (!pipe) return "ERROR";
    char buffer[128];
    std::string result = "";
    while(!feof(pipe)) {
        if(fgets(buffer, 128, pipe) != NULL)
                result += buffer;
    }
    pclose(pipe);
    return result;
}
   
           
};

