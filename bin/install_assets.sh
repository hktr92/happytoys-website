#!/usr/bin/env bash

#output="dependencies.gen.json"
basedir=$(dirname $PWD)
libdir=${basedir}/backend/lib/bower
output=${basedir}/public_html/assets

declare -a mincss=("reset" "container" "grid" "header" "image" "menu" "divider" "dropdown" "segment" "button" "list" "icon" "sidebar" "transition" "font-awesome")
declare -a css=("site" "happy")

declare -a minjs=("jquery" "visibility" "transition" "sidebar")
declare -a js=("happy")

declare -a dirs=("fonts" "jpg" "png" "pdf" "mp4")

#if [[ -e ${output} ]]; then
#    rm ${output}
#fi

#echo "[" >> $output
#for item in ${mincss[@]}; do
#    filename=$(find ${libdir} -type f -name ${item}.min.css)
#    echo "\"${filename}\"," >> ${output}
#done
#echo "]" >> $output

function copy_template
{
    local operation_tip=$1
    local operation_val=$2
    local operation_dir=${3:-'f'}

    if [[ -z ${operation_tip} ]] && [[ "${operation_dir}" != 'd' ]]; then
        echo "You must specify the operation type!"
        return 1
    fi

    if [[ -z ${operation_val} ]]; then
        echo "You must specify the operation value!"
        return 1
    fi

    if [[ -z ${operation_dir} ]]; then
        echo "You must specify the node type [d(irectory)|f(ile)]"
        return 1
    fi

    #echo $operation_dir
    #if [[ "${operation_dir}" != "f" ]] || [[ "${operation_dir}" != "d" ]]; then
    #    echo "Only two types are allowed for usage in 'find': f(ile) and d(irectory)"
    #    return 1
    #fi

    if [[ ${operation_dir} == 'f' ]]; then
        local findname="${operation_val}.${operation_tip}"
    else
        local findname="${operation_val}"
    fi

    filename=$(find ${libdir} -type ${operation_dir} -name ${findname})

    if [[ -z ${filename} ]]; then
        echo -e "\t[ERROR]: ${findname}: file non-existent!"
    else
        if [[ "${operation_tip}" == "min.css" ]]; then
            local operation_tip="css"
        fi

        if [[ "${operation_tip}" == "min.js" ]]; then
            local operation_tip="js"
        fi

        if [[ ! -d ${output}/${operation_tip} ]] && [[ ! -z ${operation_tip} ]]; then
            mkdir -p ${output}/${operation_tip}
        fi

        echo -e "\t [OK]: ${findname}"
        if [[ ${operation_dir} == 'd' ]]; then
            local args='-rf'
        elif [[ ${operation_dir} == 'f' ]]; then
            local args='-f'
        fi

        cp ${args} ${filename} ${output}/${operation_tip}
    fi
}

function main
{
    if [[ -d ${output} ]]; then
        rm -rf ${output}
    fi

    if [[ ! -d ${output} ]]; then
        mkdir ${output}
    fi

    echo "Copying minified css..."
    for item in ${mincss[@]}; do
        copy_template "min.css" "${item}"
    done
    echo "Copy done!"

    echo "Copying css..."
    for item in ${css[@]}; do
        copy_template "css" "${item}"
    done
    echo "Copy done!"

    echo "Copying minified js..."
    for item in ${minjs[@]}; do
        copy_template "min.js" "${item}"
    done
    echo "Copy done!"

    echo "Copying js..."
    for item in ${js[@]}; do
        copy_template "js" "${item}"
    done
    echo "Copy done!"

    echo "Copying directories..."
    for item in ${dirs[@]}; do
        copy_template "" "${item}" "d"
    done
    echo "Copy done!"
}

main $@
exit $?