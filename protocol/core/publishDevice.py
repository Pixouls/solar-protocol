"""
Every server runs this script, which posts its own IP address + other data to:

* all devices in devices.json
* localhost
* solarprotocol.net
"""

# these modules are only used by this module within this packages
# all other modules are imported via __init__
import re
from threading import local
import time
import requests
import json
import subprocess
import os
import sys
from logging import error, info

from solar_secrets import getSecret, SecretKey

poeLog = "/data/poe.log"
localConfig = "/local/local.json"
deviceList = "/data/devices.json"

# this only works with linux
# FIXME: there is a problem using 00:00 as a duplicate identifier
def getmac(interface: str = "wlan0"):
    try:
        mac = open(f"/sys/class/net/{interface}/address").readline()
    except:
        mac = "00:00:00:00:00:00"

    return mac


def getDevices(key: str):
    with open(deviceList) as file:
        devices = json.load(file)

    return [device[key] for device in devices]


def getPoeLog():
    try:
        file = open(poeLog)
        # take the first 216 lines
        lines = poeFile.readlines()[:216]
        # if solarProtocol.py runs every 10 minutes, there can be max 432 entries
        # this would happen if the current server was POE for the entire 72 hours
        poeLog = [line.removeprefix("INFO:root:") for line in lines]
        return ",".join(poeLog)

    except:
        return ""


def getLocal(key):
    try:
        with open(localConfig) as file:
            device = json.load(file)
            return device[key]

    except err:
        error(f"local config file exception with key {key}")
        error(err)


"""
For every device in our local devices.json, ask for their device lists
FIXME: lets discuss exactly how chatty this is
"""


def discoverIps():
    ips = getDevices("ip")
    macs = getDevices("macs")

    all_devices = []

    for ip in ips:
        devices = requests.get(f"http://{ip}/api/devices").json()
        all_devices.append(devices)

    all_macs = {device["mac"] for device in all_devices}
    local_macs = {macs}

    new_macs = all_macs - local_macs
    new_devices = {all_devices.filter(lambda device: device["mac"] in new_macs)}

    outputToConsole(f"new ips: {[ device['ip'] for device in new_devices ]}")

    discoveredIps = [device["ip"] for device in newDevices]

    return discoveredIps


def postDevice(ip, params):
    url = f"http://{ip}/api/device"
    headers = {"Content-Type": "application/x-www-form-urlencoded"}

    try:
        response = requests.post(url=url, headers=headers, params=params, timeout=5)
        if response.ok:
            info(f"Post to {ip} successful")
        else:
            error(f"Malformatted response from {ip}", response.text)
    except json.decoder.JSONDecodeError as err:
        error(f"JSON decoding error", err)
    except requests.exceptions.HTTPError as err:
        error(f"An Http Error occurred: {repr(err)}")
    except requests.exceptions.ConnectionError as err:
        error(f"An Error Connecting to the API occurred: {repr(err)}")
    except requests.exceptions.Timeout as err:
        error(f"A Timeout Error occurred: {repr(err)}")
    except requests.exceptions.RequestException as err:
        error(f"An Unknown Error occurred: {repr(err)}")


def publishDevice(ips, device, log):
    metadata = {
        "apiKey": getApiKey(),
        "timestamp": time.now(),
    }

    params = device | log | metadata

    for ip in ips:
        info(f"IP: {ip}")
        postDevice(ip, params)


def getApiKey():
    return "this-will-fail"

    return getSecret(SecretKey.apiKey)


def getDevice():
    # FIXME: should we remove server. to make fully p2p?
    ip = requests.get("https://server.solarpowerforartists.com/?myip").text
    httpPort = getLocal("httpPort") or "80"
    MAC = getmac("wlan0")  # change to eth0 if using an ethernet cable

    name = getLocal("name")
    # only allow alphanumeric, space, and _ characters
    name = re.sub("[^A-Za-z0-9_ ]+", "", name)

    # get my timezone
    tz = requests.get(f"http://localhost:{httpPort}/system", params={"key": "tz"}).text

    device = {
        "api_key": str(api_key),
        "stamp": str(time.time()),
    }

    return {
        "ip": ip,
        "httpPort": myHttpPort,
        "mac": mac,
        "name": name,
        "tz": tz,
        "log": log,
    }


def run():
    info()
    info("***** Running PublishDevice script *****")
    info()

    apiKey = getApiKey()
    device = getDevice()

    knownIps = getDevices("ip")
    selfIp = "localhost:11221"
    activeIp = "solarprotocol.net"
    discoveredIps = discoverIps()

    # post to self
    publishDevice([selfIp], apiKey, device)

    # post to solarprotocol.net
    publishDevice([activeIp], apiKey, device)

    # post to known ips
    publishDevice(knownIps, apiKey, device)

    # post to discovered ips
    publishDevice(discoveredIps, apiKey, device)


def outputToConsole(message):
    if consoleOutput:
        info(message)


if __name__ == "__main__":
    run()
else:
    consoleOutput = False
