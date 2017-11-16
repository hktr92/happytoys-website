#!/usr/bin/env bash

basedir=$(dirname $PWD)
srcdir=${basedir}/backend/var/targuri
outdir=${basedir}/public_html/assets

function manage
{
    local workdir=$1

    if [[ -z $workdir ]]; then
        echo "No workdir defined as parameter!"
        return 1
    fi

    if [[ ! -d ${srcdir}/${workdir} ]]; then
        echo "Nothing to work, sir."
        return 1
    fi

    if [[ ! -d ${srcdir}/${workdir}/images ]]; then
        echo "No images to work with :("
        return 1
    fi

    if [[ ! -e ${srcdir}/${workdir}/meta.ht ]]; then
        echo "No info regarding these photos! :("
        return 1
    fi

    if [[ ! -e ${outdir}/jpg ]]; then
        echo "Creating new assets directory..."
        mkdir -p ${outdir}/jpg
    fi

    local prefix=$(cat ${srcdir}/${workdir}/meta.ht)
    local count=$(ls -l ${srcdir}/${workdir}/images | wc -l)

    incr=0
    echo "Working for '${workdir}'..."
    for file in $(ls ${srcdir}/${workdir}/images); do
        incr=$((incr+1))

        echo "${file} -> ${prefix}_${incr}.jpg"
        cp ${srcdir}/${workdir}/images/${file} ${outdir}/jpg/${prefix}_${incr}.jpg
    done
}

function main
{
    echo "Copying files..."
    manage "targfe_20151205"
    manage "targfe_20160401"
    echo "Copy done."
}

main $@
exit $?

