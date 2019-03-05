<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();?>

<div class="l-report-form">
    <div class="b-title b-reports-title">
        <h1>Отчет&nbsp;о&nbsp;деятельности резидента</h1>
    </div>

    <h2 class="b-reports-subtitle">1 квартал, 2018</h2>

    <div class="b-inputs-row b-report-oez">
        <div class="b-input-block j-report-resident-name">
            <input id="resident-name" class="b-input-text" type="text" name="resident-name" maxlength="" autocomplete="" value="" placeholder="">

            <label class="b-input-block__label" for="resident-name">Наименование резидента ОЭЗ</label>
        </div>
        <div class="b-input-block j-report-oez-name">
            <input id="oez-name" class="b-input-text" type="text" name="oez-name" maxlength="" autocomplete="" value="" placeholder="">

            <label class="b-input-block__label" for="resident-name">Наименование ОЭЗ</label>
        </div>
    </div>
    <form action="#" class="b-reports-filter b-mini-filter j-reports-filter">

        <div class="b-mini-filter__group j-reports-select-group">

            <div class="b-mini-filter__values j-reports-select" data-title-default="Ф-1 Общая"><span class="j-reports-select-title">Ф-1 Общая</span></div>

            <div class="b-mini-filter__group-wrap">
                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form1" data-text="Ф-1 Общая" id="form1-1" class="b-mini-filter__input" checked="">
                    <label for="form1-1" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-1 Общая</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form2" data-text="Ф-2 Налоги" id="form2-1" class="b-mini-filter__input">
                    <label for="form2-1" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-2 Налоги</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form3" data-text="Ф-3 Стройка" id="form3-1" class="b-mini-filter__input">
                    <label for="form3-1" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-3 Стройка</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form4" data-text="Ф-4 Аренда" id="form4-1" class="b-mini-filter__input">
                    <label for="form4-1" class="b-mini-filter__fake b-mini-filter__fake_is_success">Ф-4 Аренда</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form5" data-text="Ф-5 ППТ, ТВ и ПТ" id="form5-1" class="b-mini-filter__input">
                    <label for="form5-1" class="b-mini-filter__fake">Ф-5 ППТ, ТВ&nbsp;и&nbsp;ПТ</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form6" data-text="Ф-6 ТВ-3" id="form6-1" class="b-mini-filter__input">
                    <label for="form6-1" class="b-mini-filter__fake b-mini-filter__fake_is_success">Ф-6 ТВ-3</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form7" data-text="Ф-7 ТВД и ИД" id="form7-1" class="b-mini-filter__input">
                    <label for="form7-1" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-7 ТВД и&nbsp;ИД</label>
                </div>
            </div>
        </div>
    </form><div class="b-report-comments">
        <h3 class="b-report-comments__title">Замечания</h3>
        <div class="b-report-comments__body">
            <a href="#block-4">Пункт №4</a>
            <a href="#block-7">Пункт №7</a>
        </div>
    </div>


    <!--
        data-read-only Просмотр уже принятых отчетов за предыдущие кварталы
    -->
    <div class="b-report-form j-report-form" data-current-form="0"><div>
            <section class="b-report-block j-report-block b-report-block_status_approved" id="block-1" data-approved="">
                <div class="b-report-block__header" data-number="1">
                    <h3 class="b-report-block__title">Участие иностранных инвесторов, в составе акционеров (участников) или прямые иностранные инвестиции</h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-radio-row j-foreign-investors-switch">
                        <div class="b-custom-radio">
                            <input id="foreign-investors-yes" class="b-custom-radio__input" type="radio" value="yes" name="foreign-investors">
                            <label for="foreign-investors-yes" class="b-custom-radio__label">
                                <span class="b-custom-radio__icon"></span>
                                <span class="b-custom-radio__text">Да</span>
                            </label>
                        </div>

                        <div class="b-custom-radio">
                            <input id="foreign-investors-no" class="b-custom-radio__input" type="radio" value="no" name="foreign-investors">
                            <label for="foreign-investors-no" class="b-custom-radio__label">
                                <span class="b-custom-radio__icon"></span>
                                <span class="b-custom-radio__text">Нет</span>
                            </label>
                        </div>
                    </div>

                    <div class="b-input-block j-foreign-investors-field">
                        <input id="investors-countries" class="b-input-text" type="text" name="investors-countries" maxlength="" autocomplete="" value="" placeholder="">

                        <label class="b-input-block__label" for="investors-countries">Страны</label>
                    </div>
                </div>
            </section>

            <section class="b-report-block j-report-block" id="block-2">
                <div class="b-report-block__header" data-number="2">
                    <h3 class="b-report-block__title">Количество рабочих мест, созданных на территории ОЭЗ,&nbsp;(ед.)</h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-report-block__subtitle">В соответствии с бизнес-планом</div>
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="jobs-plan-all" class="b-input-text" type="text" name="jobs-plan-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="jobs-plan-all">Всего</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="jobs-plan-year" class="b-input-text" type="text" name="jobs-plan-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="jobs-plan-year">За текущий год</label>
                        </div>
                    </div>

                    <div class="b-report-block__subtitle ">
                        Фактически созданных<div class="b-help j-help">С начала деятельности в качестве резидента</div>
                    </div>
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="jobs-actual-all" class="b-input-text" type="text" name="jobs-actual-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="jobs-actual-all">С начала деятельности в качестве резидента</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="jobs-actual-year" class="b-input-text" type="text" name="jobs-actual-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="jobs-actual-year">За текущий год</label>
                        </div>
                    </div>
                </div>
            </section>

            <section class="b-report-block j-report-block b-report-block_status_approved" id="block-3" data-approved="">
                <div class="b-report-block__header" data-number="3">
                    <h3 class="b-report-block__title">Объем заявленных инвестиций на территории ОЭЗ (в соответствии с&nbsp;бизнес-планом),&nbsp;млн,&nbsp;₽</h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-report-block__subtitle">Общий объем</div>
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="invests-plan-all" class="b-input-text" type="text" name="invests-plan-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="invests-plan-all">Всего,&nbsp;млн&nbsp;₽</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="invests-plan-year" class="b-input-text" type="text" name="invests-plan-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="invests-plan-year">За текущий год,&nbsp;млн&nbsp;₽</label>
                        </div>
                    </div>

                    <div class="b-report-block__subtitle">в т.ч. капитальных вложений</div>
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="capital-invests-plan-all" class="b-input-text" type="text" name="capital-invests-plan-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="capital-invests-plan-all">Всего,&nbsp;млн&nbsp;₽</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="capital-invests-plan-year" class="b-input-text" type="text" name="capital-invests-plan-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="capital-invests-plan-year">За текущий год,&nbsp;млн&nbsp;₽</label>
                        </div>
                    </div>
                </div>
            </section>

            <section class="b-report-block j-report-block b-report-block_status_error" id="block-4" data-has-error="">
                <div class="b-report-block__header" data-number="4">
                    <h3 class="b-report-block__title">Общий объем осуществленных инвестиций на территории ОЭЗ
                        <span class="b-help j-help">Общий объем осуществленных инвестиций на территории ОЭЗ</span>
                    </h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-report-block__subtitle">Общий объем</div>
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="invests-all" class="b-input-text" type="text" name="invests-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="invests-all">С начала деятельности в качестве резидента,&nbsp;млн&nbsp;₽</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="invests-year" class="b-input-text" type="text" name="invests-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="invests-year">С начала текущего года,&nbsp;млн&nbsp;₽</label>
                        </div>
                    </div>

                    <div class="b-report-block__subtitle">в т.ч. капитальных вложений</div>
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="capital-invests-all" class="b-input-text" type="text" name="capital-invests-all" maxlength="" autocomplete="" value="" placeholder="" data-has-error="">

                            <label class="b-input-block__label" for="capital-invests-all">Всего,&nbsp;млн&nbsp;₽</label>
                            <div class="b-input-error">
                                <div class="b-input-error__message">В предыдущем отчете капитальных вложений было 100. Перепроверьте данные.</div>
                                <button class="b-input-error__confirm" type="button">Подтвердить данные</button>
                            </div>
                        </div>

                        <div class="b-input-block ">
                            <input id="capital-invests-year" class="b-input-text" type="text" name="capital-invests-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="capital-invests-year">За текущий год,&nbsp;млн&nbsp;₽</label>
                        </div>
                    </div>

                </div>
            </section>

            <section class="b-report-block j-report-block b-report-block_status_approved" id="block-5" data-approved="">
                <div class="b-report-block__header" data-number="5">
                    <h3 class="b-report-block__title">
                        Объем выручки от продажи товаров, продукции, работ, услуг (в денежном выражении,&nbsp;млн&nbsp;₽)
                        <span class="b-help j-help">Объем выручки от продажи товаров, продукции, работ, услуг (в денежном выражении,&nbsp;млн&nbsp;₽)</span>
                    </h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="revenue-all" class="b-input-text" type="text" name="revenue-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="revenue-all">С начала деятельности в качестве резидента,&nbsp;млн&nbsp;₽</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="revenue-year" class="b-input-text" type="text" name="revenue-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="revenue-year">С начала текущего года,&nbsp;млн&nbsp;₽</label>
                        </div>
                    </div>
                </div>
            </section>

            <section class="b-report-block j-report-block b-report-block_status_approved" id="block-6" data-approved="">
                <div class="b-report-block__header" data-number="6">
                    <h3 class="b-report-block__title">Объем произведенной продукции (работ, услуг) на территории ОЭЗ, (в денежном выражении,&nbsp;млн&nbsp;₽)</h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-inputs-row">
                        <div class="b-input-block ">
                            <input id="produce-all" class="b-input-text" type="text" name="produce-all" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="produce-all">С начала деятельности в качестве резидента,&nbsp;млн&nbsp;₽</label>
                        </div>
                        <div class="b-input-block ">
                            <input id="produce-year" class="b-input-text" type="text" name="produce-year" maxlength="" autocomplete="" value="" placeholder="">

                            <label class="b-input-block__label" for="produce-year">С начала текущего года,&nbsp;млн&nbsp;₽</label>
                        </div>
                    </div>
                </div>
            </section>

            <section class="b-report-block j-report-block b-report-block_status_error" id="block-7" data-has-error="">
                <div class="b-report-block__header" data-number="7">
                    <h3 class="b-report-block__title">Средняя з/п сотрудников предприятия (₽), (без учета руководящего состава)</h3>
                </div>
                <div class="b-report-block__body">
                    <div class="b-input-block ">
                        <input id="salary" class="b-input-text" type="text" name="salary" maxlength="" autocomplete="" value="" placeholder="" data-has-error="">

                        <label class="b-input-block__label" for="salary">З/п сотрудников,&nbsp;₽</label>
                        <div class="b-input-error">
                            <div class="b-input-error__message">В предыдущем отчете з/п сотрудников была 80&nbsp;000 ₽. Перепроверьте данные</div>
                            <button class="b-input-error__confirm" type="button">Подтвердить данные</button>
                        </div>
                    </div>
                </div>
            </section>
        </div></div>

    <form action="#" class="b-reports-filter b-reports-filter_position_bottom b-mini-filter j-reports-filter">

        <div class="b-mini-filter__group j-reports-select-group">

            <div class="b-mini-filter__values j-reports-select" data-title-default="Ф-1 Общая"><span class="j-reports-select-title">Ф-1 Общая</span></div>

            <div class="b-mini-filter__group-wrap">
                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form1" data-text="Ф-1 Общая" id="form1-2" class="b-mini-filter__input" checked="">
                    <label for="form1-2" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-1 Общая</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form2" data-text="Ф-2 Налоги" id="form2-2" class="b-mini-filter__input">
                    <label for="form2-2" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-2 Налоги</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form3" data-text="Ф-3 Стройка" id="form3-2" class="b-mini-filter__input">
                    <label for="form3-2" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-3 Стройка</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form4" data-text="Ф-4 Аренда" id="form4-2" class="b-mini-filter__input">
                    <label for="form4-2" class="b-mini-filter__fake b-mini-filter__fake_is_success">Ф-4 Аренда</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form5" data-text="Ф-5 ППТ, ТВ и ПТ" id="form5-2" class="b-mini-filter__input">
                    <label for="form5-2" class="b-mini-filter__fake">Ф-5 ППТ, ТВ&nbsp;и&nbsp;ПТ</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form6" data-text="Ф-6 ТВ-3" id="form6-2" class="b-mini-filter__input">
                    <label for="form6-2" class="b-mini-filter__fake b-mini-filter__fake_is_success">Ф-6 ТВ-3</label>
                </div>

                <div class="b-mini-filter__item">
                    <input type="radio" name="reports-forms" value="form7" data-text="Ф-7 ТВД и ИД" id="form7-2" class="b-mini-filter__input">
                    <label for="form7-2" class="b-mini-filter__fake b-mini-filter__fake_is_error">Ф-7 ТВД и&nbsp;ИД</label>
                </div>
            </div>
        </div>
    </form>

    <button class="button button_without_icon b-report-submit j-report-submit" type="button" disabled="">Отправить отчет</button>
</div>
