#Takes all .pdf's from pdf_dir and converts them to .png's at png_dir
pdf_to_png(){

    # $1=pdf_dir, $2=png_dir
    echo -n "Working: ["
    for file in ${1}/*.pdf ; do

        pdf_name=${file##*/}
        convert -density 50 -trim -fuzz 1% $file ${2}/${pdf_name%.pdf}.png
        echo -n "#"

    done

    echo -n "]"
    echo " "

}


# Set up autoplotter in public_html if it exists
setup(){

    # $1 : public_html directory

    if [[ -e ${1} ]] ; then
        mkdir ${1}/${2}
        mkdir ${1}/${2}/pdfs
        mkdir ${1}/${2}/pngs
        cp index.php ${1}/${2}
        touch ${1}/${2}/.AutoPlotter
        
    else
        echo "Public html directory not found, please log in to your UAF account to complete set up."
        exit 0

    fi
}

updt(){

    # $1 : target directory, $2 : origin directory
    if [[ -e ${1}/.AutoPlotter ]] ; then

        if [[ -e ${1}/pdfs ]] ; then

            rm -r ${1}/pdfs ${1}/pngs
            mkdir ${1}/pdfs ${1}/pngs
            
            for file in ${2}/*.pdf ; do
                cp ${file} ${1}/pdfs
            done
            
            pdf_to_png ${1}/pdfs ${1}/pngs
            chmod -R 755 ${1}/pdfs
            chmod -R 755 ${1}/pngs
            chmod -R 755 ${1}

            exit 0
        fi

    else
        echo "Please run the setup command first."
        exit 0
    fi
}

html=/home/users/${USER}/public_html

if [ "${1}" == "setup" ] ; then
    # $2 : name of plot directory
    setup ${html} ${2}
    exit 0
    
elif [ "${1}" == "updt" ] ; then

    # $2 : target directory, $3 : origin directory

    if ! [[ -e ${3} ]] ; then
        echo "Directory ${3} does not exist"
        exit 0
    fi

    updt ${html}/${2} ${3}
    exit 0

else
    echo "Invalid command: ${1}"
    exit 0
fi
