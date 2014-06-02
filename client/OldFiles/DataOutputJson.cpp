/******************************************************************************
 *
 * DataOutputJson.cpp - Simple object to store json for output stream.
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

#include <string>
#include <iostream>
#include <stdio.h>
// strings and c-strings
#include <iostream>
#include <cstring>
#include <string>
using namespace std;

class DataOutputJson{
private:
    string json;
public:
    DataOutputJson(){}
    void setJson(string strJson){
        json = strJson;
    }

    string getJson(){
        return json;
    }
};

