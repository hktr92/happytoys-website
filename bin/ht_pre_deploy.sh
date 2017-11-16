#!/usr/bin/env bash

basedir=$(dirname $PWD)

text_notice="\x1B[33m"
text_reset="\x1B[0m"

bower_deps=("ckeditor" "font-awesome" "jquery" "semantic")

function main
{
    echo -e "${text_notice}[hook] clearing cache...${text_reset}"
    cd ${basedir}/backend/bin
    php console cache:clear
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] removing bower dependencies...${text_reset}"
    cd ${basedir}/backend/lib/bower
    rm -rf ${bower_deps[@]}
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] removing composer dependencies...${text_reset}"
    cd ${basedir}/backend/lib
    rm -rf composer
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"

    echo -e "${text_notice}[hook] removing assets...${text_reset}"
    cd ${basedir}/public_html
    rm -rf assets
    cd ${basedir}
    echo -e "${text_notice}[hook] completed${text_reset}"
}

main $@
exit $?