import Utils from 'common/scripts/utils';

class Logout {
    /**
     * Инициализация параметров
     * @param {Object} options - настройик компонента (button - элемент на котрый биндится обработка клика)
     */
    init(options) {
        options.button.addEventListener('click', () => {
            Utils.send('logout', '/tests/logout.json', {
                success(response) {
                    const successStatus = 1;

                    if (response.request.status === successStatus) {
                        if (response.data && response.data.backUrl) {
                            window.location.href = response.data.backUrl;
                        } else {
                            window.location.href = '/';
                        }
                    }
                },
                error(error) {
                    console.error(error);
                }
            });
        });
    }
}

export default Logout;
