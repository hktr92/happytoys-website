#!/bin/bash

output="generate_css_import.gen.css"
mincss=("reset" "container" "grid" "header" "image" "menu" "divider" "dropdown" "segment" "button" "list" "icon" "sidebar" "transition" "font-awesome")
css=("site")

if [[ -e "${output}" ]]; then
    rm ${output}
fi

echo "/* minified css generation */" >> ${output}
for item in ${mincss[@]}; do
	echo "@import '${item}.min.css';" >> ${output}
done
echo "/* ====== */" >> ${output}
echo >> ${output}

echo "/* standard css generation */" >> ${output}
for item in ${css[@]}; do
	echo "@import '${item}.css';" >> ${output}
done
echo "/* ====== */" >> ${output}
echo >> ${output}
