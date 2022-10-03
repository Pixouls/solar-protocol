# development setup

We use containers because python package management is painful

## install container tools

Always check [the latest installation docs](https://podman.io/getting-started/installation)

### linux

    sudo apt install podman

### macOS

    brew install podman
    podman machine init --volume /Users

> Ignore any `Error: exit status 255` messages

Why the `--volume /Users`?

Podman and Docker only work on linux, so if we want to run them on macOS, we have to start a linux virtual machine. And we first need to mount files from macOS to linux, before we can use them in our containers.

    macOS (host)
     -> podman machine (linux)
         -> solar-protocl-dev (container)

### Windows

    See [the official windows install docs](https://github.com/containers/podman/blob/main/docs/tutorials/podman-for-windows.md)

## build the base image

This contains a known good version of python and our dependencies

    podman build --file Containerfile --tag solar-protocol

## run a shell

This will start a temporary shell in the known good environment

    podman run --rm --interactive --tty \
      --name solar-protocol-dev \
      --mount type=bind,source="$(pwd)",target=/app \
      localhost:solar-protocol \
      /bin/bash
