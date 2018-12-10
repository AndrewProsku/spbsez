const fs = require('fs');
const path = require('path');
const dirPath = path.resolve(__dirname, '../../src/components');

function template (name) {
    return `<div class="b-${name}">
    
</div>
`;
}

module.exports = function(name) {
    const file = `${dirPath}/${name}/${name}.twig`;

    fs.writeFile(file, template(name), err => {
        if (err) throw err;
        console.info('\x1b[36m%s\x1b[0m', 'Create twig. Done!');
    });
};
