/******************************************************************************
 *
 * Instance.cpp - Read configuration file(info.conf). Create JSON string and send it to server.
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

#import "DataOutputJson.cpp"
#include <string>
#include <iostream>
#include <stdio.h>
// strings and c-strings
#include <iostream>
#include <cstring>
#include <string>
#include <iostream>
#include <fstream>
#include <string>
#include <sstream>
#include <sys/time.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

#include <curl/curl.h> //your directory may be different
//PARA COMPILAR: g++ libcurl1.cpp -lcurl
using namespace std;


class Instance{
private:
    string itoken;
    int timesamples;
    string serverurl;
public:
    string execCommand(string st) {


	int sizecmd=st.size();
	char charcmd[sizecmd];
	for (int a=0;a<=sizecmd;a++)//Convertendo de string para char
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
       string getIPAddress(){
           return execCommand("./ipadress");
       }
       void readConfiguration(){
           itoken = execCommand("cat info.conf | grep ITOKEN= | cut -c 8-");
           istringstream(execCommand("cat info.conf | grep TIMESAMPLES= | cut -c 13-")) >> timesamples;
           serverurl = execCommand("cat info.conf | grep SERVERURL | cut -c 11-");
       }
       void display_settings(){
           cout << "ITOKEN: " << itoken << endl;
           cout << "TIMESAMPLES: " << timesamples << endl;
           cout << "SERVERURL:" << serverurl << endl;
       }

string data; //will hold the url's contents

size_t writeCallback(char* buf, size_t size, size_t nmemb, void* up)
{ //callback must have this declaration
    //buf is a pointer to the data that curl has for us
    //size*nmemb is the size of the buffer

    for (int c = 0; c<size*nmemb; c++)
    {
        data.push_back(buf[c]);
    }
    return size*nmemb; //tell curl how many bytes we handled
}


       void sendData(DataOutputJson data, DataOutputJson instance){
           /*
           cout << "Sending data." << endl;
           cout << "Server:" << serverurl << endl;
           cout << "Data:" << endl;
           cout << out.getJson() << endl;*/

            CURL* curl; //our curl object

            curl_global_init(CURL_GLOBAL_ALL); //pretty obvious
            curl = curl_easy_init();

            string options_post;
            options_post="data=";
            options_post.append(data.getJson());
            //options_post.append("&info=");

            //cout << options_post ;
            


            //Conversao necessaria de string para char do campo serverurl

            cout << serverurl;
            int tmpsize=serverurl.size();
            char char_serverurl[serverurl.size()];
            for (int a=0;a<=tmpsize;a++)
                    {
                        char_serverurl[a]=serverurl[a];
                    }

            //Conversao necessaria de string para char do campo itoken e formacao da json
            string instancetoken_json = " \"InstanceToken\":\"";

            instancetoken_json.append(itoken);
            instancetoken_json.append("\"}");

            options_post.append(instancetoken_json);

            //Conversao necessaria de string para char do campo options_post
            tmpsize=options_post.size();
            char char_options_post[options_post.size()];
            for (int a=0;a<=tmpsize;a++)
                    {
                        char_options_post[a]=options_post[a];
                    }

           // cout << char_options_post;
            

            curl_easy_setopt(curl, CURLOPT_URL, char_serverurl);
            curl_easy_setopt(curl, CURLOPT_POSTFIELDS,char_options_post);
 ////           curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, &Instance::writeCallback);
        //    curl_easy_setopt(curl, CURLOPT_VERBOSE, 1L); //tell curl to output its progress

            curl_easy_perform(curl);

            //cout << endl << "data: " << data << endl;
        // mantem aberto até clicar enter.
        //    cin.get();

            curl_easy_cleanup(curl);
            curl_global_cleanup();
           
       }


long long
timeval_diff(struct timeval *difference,
             struct timeval *end_time,
             struct timeval *start_time
            )
{
  struct timeval temp_diff;

  if(difference==NULL)
  {
    difference=&temp_diff;
  }

  difference->tv_sec =end_time->tv_sec -start_time->tv_sec ;
  difference->tv_usec=end_time->tv_usec-start_time->tv_usec;

  /* Using while instead of if below makes the code slightly more robust. */

  while(difference->tv_usec<0)
  {
    difference->tv_usec+=1000000;
    difference->tv_sec -=1;
  }

  return 1000000LL*difference->tv_sec+
                   difference->tv_usec;

} /* timeval_diff() */

int getTimesamples(){
    return timesamples;
}
};
