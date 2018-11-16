const fs = require('fs');

module.exports = function(files) {
    for (let i = 0; i < files.length; i++) {
        let file = files[i];
        fs.readFile(file, 'utf8', (error, data) => {
            if (error) {
                return console.log(error);
            }
            if (file.match('.js')) {
                let reg = /urlArgs:(.*)[\'](.*)[\']/g;
                let result = data.replace(reg, string => {
                    let separateString = string.split(':');
                    return`${separateString[0]}: 'v${Date.now()}'`;
                });
                fs.writeFile(file, result, 'utf8', function (err) {
                    if (err) return console.log(err);
                });
            } else {
                let reg = /(?:href|src)="(.*)[\?]rev=(.*)[\"]/g;
                let result = data.replace(reg, string => {
                    let separateString = string.split("=");
                    return`${separateString[0]}=${separateString[1]}=${Date.now()}"`;
                });
                fs.writeFile(file, result, 'utf8', function (err) {
                    if (err) return console.log(err);
                });
            }

        });
    }
};