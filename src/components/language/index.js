class Language {
    /* eslint-disable */
    constructor() {
        this.language = document.querySelector('html').getAttribute('lang') || 'ru';
        this.data = {
            ru: {
                validation: {
                    required: 'Поле не может быть пустым',
                    email: 'Некорректный email адрес',
                    phone: 'Номер телефона введен не полностью',
                    passwordConfirm: 'Введённые пароли не совпадают'
                },
                lk: {
                    noAdmins: 'Администраторов пока нет',
                    savePassword: 'Ваш пароль успешно сохранен',
                    passwordRecovery: 'Мы выслали ссылку восстановление пароля на вашу электронную почту'
                },
                popup: {
                    closeAriaLabel: 'Закрыть всплывающее окно',
                    errors: {
                        internet: 'Вы не подключены к интернету. Повторите запрос позднее',
                        server: 'На сервере произошла ошибка. Повторите запрос позднее',
                        data: 'На сервере произошла ошибка. Повторите запрос позднее'
                    }
                },
                showMore: {
                    button: 'Показать еще'
                }
            },
            en: {
                validation: {
                    required: 'The field can not be empty',
                    email: 'Invalid email address',
                    phone: 'Phone number not fully entered',
                    passwordConfirm: 'Passwords do not match'
                },
                lk: {
                    noAdmins: 'No admins yet',
                    savePassword: 'Your password has been successfully saved.',
                    passwordRecovery: 'We have sent a password recovery link to your email.'
                },
                popup: {
                    closeAriaLabel: 'Close popup window',
                    errors: {
                        internet: 'You are not connected to the Internet. Retry the request later',
                        server: 'An error has occurred on the server. Retry the request later',
                        data: 'An error has occurred on the server. Retry the request later'
                    }
                },
                showMore: {
                    button: 'Show more'
                }
            },
            ch: {
                validation: {
                    required: '该字段不能为空',
                    email: '无效的邮件地址',
                    phone: '电话号码未完全输入',
                    passwordConfirm: '密码不匹配'
                },
                lk: {
                    noAdmins: '还没有管理员',
                    savePassword: '您的密码已成功保存。',
                    passwordRecovery: '我们已向您的电子邮件发送了密码恢复链接。'
                },
                popup: {
                    closeAriaLabel: '关闭弹出窗口',
                    errors: {
                        internet: '您未连接到Internet。 稍后重试该请求',
                        server: '服务器上发生错误。 稍后重试该请求',
                        data: '服务器上发生错误。 稍后重试该请求'
                    }

                },
                showMore: {
                    button: '显示更多'
                }
            }
        };
    }
    /* eslint-enable */

    get(search) {
        let langData = this.data[this.language];

        if (!langData) {
            console.error(`Для данного языка "${langData}" нет перевода`);

            return false;
        }

        try {
            search.split('.').forEach((val) => {
                if (!langData[val]) {
                    throw new Error(`Перевод для "${search}" не найден`);
                }

                langData = langData[val];
            });
        } catch (error) {
            console.error(error);

            return false;
        }

        return langData;
    }
}

export default Language;

