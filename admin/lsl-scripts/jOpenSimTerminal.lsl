//********************************
//* TerminalScript for jOpenSim  *
//*                              *
//* created 2011-01-20 by FoTo50 *
//* http://www.foto50.com        *
//********************************

// if you find a bug or even a security issue,
//I would be happy for a report at the support forum at http://www.foto50.com


string targetUrl			= "http://path-to-your-joomla/components/com_opensim/interface.php"; // this is the target script, handling the requests
integer listenchannel		= 555; // Channel for the Tracker to listen to
string terminalDescription	= "jOpenSim Terminal"; // This will appear as a brief description at the "Terminal Link List" of jOpenSim

// nothing interesting to change below this point!!!
key		requestId;
key		registerId;
key		response_key;
string	resident;
string	myurl;
string	querystring;
key		owner;
integer	dialogchannel;

default {
    state_entry() {
    	if(targetUrl == "http://path-to-your-joomla/components/com_opensim/interface.php") {
    		llOwnerSay("Please enter the correct path for 'targetUrl' first");
    	} else {
	        dialogchannel = (integer)(llFrand(99999.0) * -1);
	        owner = llGetOwner();
	        llRequestURL();
	        llOwnerSay("Terminal running");
	        string registerUrl = targetUrl+"?action=register&terminalDescription="+llEscapeURL(terminalDescription)+"&myurl="+myurl;
	        registerId = llHTTPRequest(registerUrl,[HTTP_METHOD,"GET"],"");
	        llListen(listenchannel, "", NULL_KEY, "");
	        llListen(dialogchannel,"", NULL_KEY,"");
	    }
    }

    listen( integer channel, string name, key id, string message ) {
        if (channel != listenchannel && channel != dialogchannel) {
            return;
        }

        if(channel == dialogchannel) {
            if(id == owner) {
                if(message == "Yes") {
                    string registerUrl = targetUrl+"?action=setState&state=1";
                    registerId = llHTTPRequest(registerUrl,[HTTP_METHOD,"GET"],"");
                }
                if(message == "No") {
                    string registerUrl = targetUrl+"?action=setState&state=0";
                    registerId = llHTTPRequest(registerUrl,[HTTP_METHOD,"GET"],"");
                }
            } else {
                if(message == "Yes") {
                    llGiveInventory(id,llGetInventoryName(INVENTORY_NOTECARD, 0));
                }
            }
        } else {

            string action = llGetSubString(message,0,7);
            string identString = llGetSubString(message,9,-1);
            if( action == "identify" ) {
                response_key = id;
                string requestUrl = targetUrl+"?action=identify&identString="+llEscapeURL(identString)+"&identKey="+(string)id;
                requestId = llHTTPRequest(requestUrl,[HTTP_METHOD,"GET"],"");
            }
        }
    }

    http_request(key id, string method, string body){    
        if (method == URL_REQUEST_GRANTED) {
            myurl=body;
            string registerUrl = targetUrl+"?action=register&terminalDescription="+llEscapeURL(terminalDescription)+"&myurl="+myurl;
            registerId = llHTTPRequest(registerUrl,[HTTP_METHOD,"GET"],"");
        } else if(method=="GET" || method=="POST") {
            querystring = llGetHTTPHeader(id,"x-query-string");
            if(querystring == "ping=jOpenSim") {
                llHTTPResponse(id,200,"ok, I am here");
            }
        }
    }

    touch_start(integer count) {
        if(llDetectedKey(0) == owner) {
            llDialog(llDetectedKey(0), "\nShow this terminal in jOpenSim?",
                 ["Yes", "No"], dialogchannel);
        } else {
            llDialog(llDetectedKey(0), "\nI am an jOpenSim terminal!\nWant to get a notecard to see what I can do for you?",
                 ["Yes", "No"], dialogchannel);
        }
    }


    http_response(key request_id, integer status, list metadata, string body) {
        if (request_id == requestId) {
            integer i = llSubStringIndex(body,resident);
            string messagestring = llGetSubString(body,i,i+llStringLength(resident)+36);
            string seentrigger = llGetSubString(messagestring,0,4);
            if(response_key != NULL_KEY) llInstantMessage(response_key,body);
        }
        if(request_id == registerId) {
            integer i = 0;
            integer end = llGetListLength(metadata);
            for (i=0; i<end; i++) {
                llOwnerSay("string=" + llList2String(metadata,i));
            }
            llOwnerSay(body);
        }
    }
}

