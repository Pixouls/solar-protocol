# Solar Protocol API V1

An API for communicating between distributed Raspberry Pis and making system data accessible

## api.php
This manages POST requests on the server

* POST requests allow other devices on the network to update the IP list to account for dynamic IP issues

### clientPostIP.py

This script updates the IPs on the other devices on the network

* this should be set on a cron timer

### solarProtocol.py
This script queries the PV data from the other devices and determines if the local device should be point of contact and updates the DNS if so

* this should be set on a cron timer
* update the subCall to the appropriate DNS updater system being used

### deviceList.json
This is where mac, ip, timestamp, and device name info from devices on the network is stored. The file needs to exist, but it can be blank... It isn't completely necessary to prepopulate it with the IP addresses, but if not you will need to manually make Post requests or edit the files locally to start everything up.
* name and timestamp aren't required, but may be helpful in the future for debugging

format:
[{"mac":"0","ip":"0.0.0.0","time stamp":"0","name":""},
{"mac":"1","ip":"1.1.1.1","time stamp":"0","name":""}]

Set file permissions for deviceList.json
* sudo chmod a+w deviceList.json

### API Key
The API key should be added to the local.json file

<!--
The API key should be changed and stored as an environmental variable on each device
* The environmental variable key is SP_API_KEY

Setting environmental variables on the Pi (source https://linuxize.com/post/how-to-set-and-list-environment-variables-in-linux/)
* Variables set in the /etc/profile file are loaded whenever a bash login shell is entered. You may need to reboot after adding the variables to this file.
* When declaring environment variables in this file you need to use the export command. Do not put a space around the =.
* Add this line to bottom of /etc/profile (replace this temp key with a new one)
	* export SP_API_KEY=tPmAT5Ab3j7F9
-->


### POST

Possible keys for Post requests:
* apiKey
* stamp - time stamp
* ip
* mac
* name - name of device
* log - log of "point of contact" events

Python Example: 

import requests


headers = {
    'Content-Type': 'application/x-www-form-urlencoded',
}

myString = "api_key="+apiKey+"&stamp="+str(time.time())+"&ip="+myIP+"&mac="+myMAC+"&name="+myName
x = requests.post('http://www.mywebsite.xyz/api/v1/api.php', headers=headers,data = myString)

## chargecontroller.php
This manages open access GET requests for local charge controller data

* GET requests allow for querying PV data from the device

### Syntax

clientGetPV.py is just for testing purposes. solarProtocol.py handles get request when they system is operational. You can also use a browser to make a get request.

Possible keys for get requests

* value - returns the specified value from the most recently collected line of data
	* Example: http:// + URL + /api/v1/chargecontroller.php?value=PV-voltage
	* Possible values (replace spaces with "-"):
		* PV current
		* PV power H
		* PV power L
		* PV voltage
		* battery percentage
		* battery voltage
		* charge current
		* charge power H
		* charge power L
		* load current
		* load power
		* load voltage
		* datetime
* line - returns the specified line from the current data logger file with headers in JSON format
	* Example: http:// + URL + /api/v1/chaergecontroller.php?line=0
	* Possible values:
		* len - returns the number of rows in the file
		* head - returns the column headers
		* 0 - returns the most recently collected line of data
		* increment up to move back in time from 0 to retrieve any other row. For example, 1 will return the 2nd most recent row.
* file - returns a specific file
	* Example: http:// + URL + /api/v1/chargecontroller.php?file=len
	* Possible values:
		* deviceList - returns the deviceList.json file contents (should be changed to a POST not a GET)
		* 1 - returns present day data 
		* 2 - returns present day + previous day
		* 3 - returns present day + previous 2 days
		* 4 - returns present day + previous 3 days
		* 5 - returns present day + previous 4 days
		* 6 - returns present day + previous 5 days
		* 7 - returns present day + previous 6 days
		* list - returns list of all CC files
		* len - return count of all CC files
		* [file name without file suffix] - example: /api/v1/api.php?file=tracerData2020-05-17

<p>
Browser Example: http://solarprotocol.net/api/v1/api.php?value=PV-voltage would return the most recent PV voltage
</p>