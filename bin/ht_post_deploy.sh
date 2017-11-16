#!/usr/bin/env bash

basedir=$(dirname $PWD)

text_notice="\x1B[33m"
text_reset="\x1B[0m"

function main
{
    echo -e "${text_notice}[hook] installing bower dependencies...${text_reset}"
    cd ${basedir}/backend/lib
    bower install
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] installing composer dependencies...${text_reset}"
    cd ${basedir}/backend/lib
    composer install
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] clearing cache...${text_reset}"
    cd ${basedir}/backend/bin
    php console cache:clear
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] installing assets...${text_reset}"
    cd ${basedir}/_tools
    bash assets.sh
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] installing photo gallery...${text_reset}"
    cd ${basedir}/_tools
    bash organize_photos.sh
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] granting special permissions...${text_reset}"
    cd ${basedir}/backend
    chmod 0777 var
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"
}

main $@
exit $?