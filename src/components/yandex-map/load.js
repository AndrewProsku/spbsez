export default function() {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');

        script.src = `https://api-maps.yandex.ru/2.1/?lang=ru_RU`;
        script.async = true;
        script.defer = true;
        script.type = 'text/javascript';
        script.onload = resolve;
        script.onerror = (error) => {
            reject(error);
        };

        document.body.appendChild(script);
    })
        .then(() => {
            return new Promise((resolve) => {
                window.ymaps.ready(resolve);
            });
        });
}

