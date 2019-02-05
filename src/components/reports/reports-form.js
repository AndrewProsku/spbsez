// import $ from 'jquery';
import Mediator from 'common/scripts/mediator';
import ReportBlock from './report-block';
import Utils from '../../common/scripts/utils';

const mediator = new Mediator();

class ReportForm {
    constructor() {
        this.target = null;
        this.formBlocks = [];
        // возможно флаг не нужен
        this.isFormApproved = false;

        this.SUCCESS_STATUS = 1;
        this.FAIL_STATUS = 0;
    }

    init(options) {
        this.target = options.target;
        this.formBlocks = Array.from(this.target.querySelectorAll('.j-report-block'));
        const that = this;


        mediator.subscribe('blockStatusChanged', () => {
            for (let i = 0; i < this.formBlocks.length; i++) {
                if (this.formBlocks[i].dataset.approved !== 'true') {
                    this.isFormApproved = false;
                    this.target.dataset.approved = false;

                    return;
                }
            }
            this.target.dataset.approved = true;
            this.isFormApproved = true;
        });

        // по умолчанию поля считаются валидными
        // иначе добавляеся поле isInvalid и массив errors
        Utils.send('', '/tests/reports/first.json', {
            success(response) {
                if (response.request.status === that.FAIL_STATUS) {
                    return;
                }
                const data = response.data;

                that.initFormBlocks(data.blocks);
            },
            error(error) {
                console.error(error);
            }
        });
    }

    bindEvents() {
        // Отсылаем введенные пользователем данные при изменении значения тестовых полей
        // this.adminInputs.forEach((input) => {
        //     input.addEventListener('change', (event) => {
        //         this.onChange(event.target);
        //     });
        // });
    }

    initFormBlocks(blocksData) {
        blocksData.forEach((blockData, i) => {
            const reportBlock = new ReportBlock();

            reportBlock.init({
                target    : this.formBlocks[i],
                inputsData: blockData
            });
        });
    }
}

export default ReportForm;
