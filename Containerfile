FROM docker.io/library/python:3.11-slim

RUN apt-get --quiet update && apt-get --quiet upgrade --yes
ENV TERM=screen-256color
COPY common /common
