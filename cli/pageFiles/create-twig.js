const fs = require('fs');
const path = require('path');
const dirPath = path.resolve(__dirname, '../../src/pages');

function template (name) {
    return `{% extends "./layout/_layout.twig" %}
{% block title %} <title>Заголовок</title> {% endblock %}

{% block content %}
    <div class="l-${name}">
        
    </div>
{% endblock %}
`;
}

module.exports = function(name) {
    const file = `${dirPath}/${name}.twig`;

    fs.writeFile(file, template(name), err => {
        if (err) throw err;
        console.info('\x1b[36m%s\x1b[0m', 'Create twig. Done!');
    });
};
