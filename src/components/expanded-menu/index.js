class ExpandedMenu {
    constructor() {
        this.$menu = document.querySelector('.j-expanded-menu');
        this.$menuHeader = this.$menu.querySelector('.b-expanded-menu__header');
        this.$menuItems = this.$menu.querySelector('.b-expanded-menu__header');
    }

    init() {
        this.$menuHeader.addEventListener('click', () => {
            this.$menu.classList.toggle('b-expanded-menu_is_open');
        });
    }
}

export default ExpandedMenu;
