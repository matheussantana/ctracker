/******************************************************************************
 *
 * vmstat.cpp - Calls the vmstat_tracker.sh script to execute the vmstat command and grab some information.
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

#include "InputDataInterface.cpp"

#include <string>
#include <iostream>
#include <stdio.h>
// strings and c-strings
#include <iostream>
#include <cstring>
#include <string>
using namespace std;

class vmstat: public InputDataInterface  {


public:
   vmstat(){
       //setCMD("vmstat");
       setCMD("./vmstat_tracker.sh");
   }
   void Parser(){

	string cmd = getCMD();
	string out = execCommand(cmd);

/*	int sizecmd=out.size();
        string tmp;
        int count = 0;
        string tmp2;
        int count2 = 0;
        for (int a=0;a<=sizecmd;a++)//Convertendo de string para char
		{
            tmp[0] = out[a];
            //cout << tmp[0];
                   if(strcmp(&tmp[0],"\n")==0){
                       count++;
                   }
                if(count == 2){
                       //cout << tmp[0];
                    tmp2[count2] = tmp[0];
                    cout << tmp2[count2];
                    count2++;}
                       //cout << endl;

		}

	char charcmd[count2];
	for (int a=0;a<=count2;a++)//Convertendo de string para char
		{
		    charcmd[a]=tmp2[a];
                   if(strcmp(&charcmd[a],"1")==0){
                       cout << "achei em: " << a << endl;
                   }

		}

*/
        json.setJson(out);
       //cout << json.getJson() << endl;
   }

};
