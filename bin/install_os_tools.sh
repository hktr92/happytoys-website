#!/bin/bash

text_notice="\x1B[33m"
text_reset="\x1B[0m"

packages=("git", "unzip", "nodejs", "npm", "lamp-server")

function install
{
    local package=$1

    if [[ -z $package ]]; then
        echo "You must specify a package!"
        return 1
    fi

    echo -e "Installing dependency: ${style_notice}${package}${style_reset}..."
    apt install -y ${package}

    if [[ $? -ne 0 ]]; then
        echo "Package ${style_notice}${package}${style_reset} installation failed; exit code=$?"
        return $?
    else
        echo "Package ${style_notice}${package}${style_reset} installation was completed!"
        return 0
    fi
}

function check
{
    if [[ "$(id -u)" -ne "0" ]]; then
        echo "You must use this script as root!"
        return 1
    else
        return 0
    fi
}

function main
{
    check

    for package in ${packages[@]}; do
        install $package
    done

    return $?
}

main $@
exit $?

