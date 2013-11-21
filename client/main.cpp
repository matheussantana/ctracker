/******************************************************************************
 *
 * main.cpp - Creates vmstat object to get the OS consumption and sends it to server. Stay in loop forever until the process is killed.
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

#include "vmstat.cpp"
#include "Instance.cpp"
#include <string>
#include <iostream>
#include <stdio.h>
// strings and c-strings
#include <iostream>
#include <cstring>
#include <string>
#include <sys/time.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

using namespace std;


/*
 * 
 */
int main(int argc, char** argv) {

   vmstat stat1;
    DataOutputJson json;
  struct timeval earlier;
  struct timeval later;
  struct timeval interval;

    stat1.Parser();
    json = stat1.getOutput();

     //cout << json.getJson() << endl;
 /*
    string st = json.getJson();

    int i;
     for(i=0;i<=json.getJson().size();i++){
         cout << st[i];

         
     }
       size_t found;

  // different member versions of find in the same order as above:
  found=st.find("\n");
  if (found!=string::npos)
    cout << "first 'needle' found at: " << int(found) << endl;
*/
    Instance inst;
    //cout << inst.getIPAddress();
    inst.readConfiguration();
    inst.display_settings();
    inst.sendData(json, json);

 if(gettimeofday(&earlier,NULL))
  {
    perror("third gettimeofday()");


  }

    while(1){

          if(gettimeofday(&later,NULL))
          {
            perror("fourth gettimeofday()");


          }
//          inst.timeval_diff(&interval,&later,&earlier);
//          if(interval.tv_sec >= inst.getTimesamples()){
                  stat1.Parser();
                  json = stat1.getOutput();
                  inst.sendData(json, json);
  //                earlier = later;
                  sleep(inst.getTimesamples());
  //        }
  //cout << "difference: " << interval.tv_sec << endl;
    }




    return (0);
}

