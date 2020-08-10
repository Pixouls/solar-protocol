# distributed-dynamic-IP-exchanger-API

## v1-files
Version 1 of the API uses CVS and JSON files.

### api.php
This manages GET and POST requests on the server

GET requests allow for querying PV data from the device
POST requests allow other devices on the network to update the IP list to account for dynamic IP issues

### clientPostIP.py

This script updates the IPs on the other devices on the network

### solarProtocol.py
This script queries the PV data from the other devices and determines if the local device should be point of contact and updates the DNS if so

### deviceList.json
This is where mac, ip and timestamp info from devices on the network is stored.

format:
[{"mac":"0","ip":"0.0.0.0","time stamp":"0"},
{"mac":"1","ip":"1.1.1.1","time stamp":"0"}]

Set file permissions for deviceList.json
* sudo chmod a+w deviceList.json

## v2-mysql
NOT FUNCTIONING

Version 2 of the API would potentially use a mysql data base, but this is (at least for the time being) more difficult, because all three servers need to have the same mysql setup i.e. same db, table, and column names as well as the same users with all necessary permissions. This approach possibly consumes less energy that v1.

https://pimylifeup.com/raspberry-pi-mysql/

GRANT ALL PRIVILEGES ON exampledb.* TO 'exampleuser'@'localhost';

## Testing

clientGetPV.py is just for testing purposes. solarProtocol.py handles this functionality in production version.

Sample data is included in the data/tracerData2020-08-04.csv file
* date on file needs to be updated daily for testing purposes
* place this in /home/pi/EPSolar_Tracer/data/


## TO DO:
* have clientPostIP.py access IP list and post to those IPs
* solarProtocol needs to access IP list and get PV data from those IPs
* merge with the solar website stuff...