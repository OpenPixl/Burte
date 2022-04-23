import printJS from "print-js";
function print(){
    printJS({
        printable: 'printFacture',
        type: 'html',
        targetStyles: ['*']
    });
    console.log('Ok')
}
document.getElementById('printButton').addEventListener ("click", print)

