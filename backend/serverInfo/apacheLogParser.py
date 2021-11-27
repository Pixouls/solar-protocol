#!/usr/bin/env python

"""
USAGE:

This script reads and parses an apache log file. It combines it with the server-status info and generates a report with:

* server creation time
* server up time
* all time total amount of unique hosts
* all time amount of unique hosts (exluding SP network devices)
* 7 days total amount of unique hosts
* 7 days total amount of unique hosts (exluding SP network devices)

Are there relevent errors to display?

"""

import csv

log_file_name = "/var/log/apache2/access.log"

csv_file_name = "server-report.csv"

#ignore these loopback addresses
ignoreHosts = ["::1","0000:0000:0000:0000:0000:0000:0000:0001","127.0.0.1","localhost"]

hosts = {}

def apache_output(line):
    split_line = line.split()
    return {'remote_host': split_line[0],
            #'apache_status': split_line[8],
            #'data_transfer': split_line[9],
    }


def final_report(logfile):
    for line in logfile:
        line_dict = apache_output(line)
        hosts[line_dict['remote_host']] = hosts[line_dict['remote_host']] + 1
        #print(line_dict)
    print(hosts)


if __name__ == "__main__":
    
    try:
        infile = open(log_file_name, 'r')
    except IOError:
        print ("You must specify a valid file to parse")
        print (__doc__)
    log_report = final_report(infile)
    print (log_report)
    infile.close()