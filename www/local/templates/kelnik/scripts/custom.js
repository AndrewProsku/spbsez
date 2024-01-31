window.onload = function(){
    sezApp.loadScrollTo();
    sezApp.generateMap();
    sezApp.faqSearch('.faq-search__field');
    sezApp.faqSlide({
        opener: 'faq-group__item-title',
        openerMod: 'faq-group__item-open_open',
        openPosition: '.faq-group__item-content',
        closePosition: 'faq-group__item-content_close',
        parentblock: '.faq-group__item',
        openerIco: '.faq-group-open'
    });
    sezApp.faqSlide({
        opener: 'faq-group__item-title-num',
        openerMod: 'faq-group__item-open_open',
        openPosition: '.faq-group__item-content',
        closePosition: 'faq-group__item-content_close',
        parentblock: '.faq-group__item',
        openerIco: '.faq-group-open'
    });
    sezApp.faqSlide({
        opener: 'faq-group-open-trigger',
        openerMod: 'faq-group-open_open',
        openPosition: '.faq-group__items',
        closePosition: 'faq-group__items_close',
        parentblock: '.faq-group',
        openerIco: '.faq-group-open'
    });
    sezApp.closeBanner({
        closer: '.banner__closer',
        banner: '.banner-wrapper'
    });
    sezApp.investCalcFields();
    Scrollbar.initAll();
    //sezApp.loadResident();

    sezApp.bannersInit();

    sezApp.reportCompareField({
        fieldsPairs: [
            ['jobs-plan-all', 'jobs-plan-year'],
            ['jobs-actual-all', 'jobs-actual-year'],
            ['invests-plan-all', 'invests-plan-year'],
            ['capital-invests-plan-all', 'capital-invests-plan-year'],
            ['invests-all', 'invests-year'],
            ['capital-invests-all', 'capital-invests-year'],
            ['revenue-all', 'revenue-year'],
            ['revenue-year-extra', 'revenue-all-extra'],
            ['produce-all', 'produce-year'],
            ['taxes-all', 'taxes-year'],
            ['taxes-federal-all', 'taxes-federal-year'],
            ['taxes-regional-all', 'taxes-regional-year'],
            ['taxes-local-all', 'taxes-local-year'],
            ['taxes-offbudget-all', 'taxes-offbudget-year'],
            ['taxes-nds-all', 'taxes-nds-year'],
            ['taxes-breaks-all', 'taxes-breaks-year'],
            ['taxes-breaks-federal-all', 'taxes-breaks-federal-year'],
            ['taxes-breaks-local-all', 'taxes-breaks-local-year'],
            ['taxes-breaks-offbudget-all', 'taxes-breaks-offbudget-year'],
            ['custom-duties-all', 'custom-duties-year'],
            ['custom-duties-breaks-all', 'custom-duties-breaks-year'],
            ['export-volume-all', 'export-volume-year']
        ]
    });
};

sezApp = {
    loadScrollTo: function(){
        let hashItems = document.querySelectorAll("a[href^='#']");
        hashItems.forEach(function(item){
            item.addEventListener('click', function (e) {
                e.preventDefault();
                let blockID = item.getAttribute('href');
                let positionTo = 'start';

                //скролл до центра плана
                if(blockID == "#area_plan_center"){
                    positionTo = 'center';
                }

                document.querySelector(blockID).scrollIntoView({
                    behavior: 'smooth',
                    block: positionTo
                });
            })
        });
    },
    investCalcOptions: {},
    investCalcFields: function(){
        let calc = document.querySelector('#investors-calc');
        if(calc){
            let checks = calc.querySelectorAll('[type=checkbox]');
            checks.forEach(function(chb){
                chb.onclick = function(){
                    if(this.checked){
                        this.closest('label').querySelector('.investors-calc__fields-field').disabled = false;
                    }else{
                        this.closest('label').querySelector('.investors-calc__fields-field').disabled = true;
                        this.closest('label').querySelector('.investors-calc__fields-field').value = 0;
                    }

                }
            });
        }
    },
    showLandRelated: function(chb){
        let lr = document.querySelectorAll('.land-related');
        lr.forEach(function(item){
            item.classList.toggle('open');
        });
    },
    investCalc: function(){
        let options = this.investCalcOptions;
        if(Object.keys(options).length < 1){
            console.log('Не установлены опции калькулятора');
            return;
        }

        //request
        let offices = Math.round(parseFloat(document.querySelector("#offices").value.replace(',', '.'))) > 0 ? Math.round(parseFloat(document.querySelector("#offices").value.replace(',', '.'))) : 0,
            production = Math.round(parseFloat(document.querySelector("#production").value.replace(',', '.'))) > 0 ? Math.round(parseFloat(document.querySelector("#production").value.replace(',', '.'))) : 0,
            land = this.decimalAdjust('round', parseFloat(document.querySelector("#land").value.replace(',', '.')), -1) > 0 ? this.decimalAdjust('round', parseFloat(document.querySelector("#land").value.replace(',', '.')), -1) : 0,
            light = Math.round(parseInt(document.querySelector("#light").value)) > 0 ? Math.round(parseInt(document.querySelector("#light").value)) : 0,
            administrative = Math.round(parseInt(document.querySelector("#administrative").value)) > 0 ? Math.round(parseInt(document.querySelector("#administrative").value)) : 0,
            science = Math.round(parseInt(document.querySelector("#science").value)) > 0 ? Math.round(parseInt(document.querySelector("#science").value)) : 0;

        let fullArea = parseInt(light) + parseInt(administrative) + parseInt(science);
        if(offices + production + land == 0){
            document.querySelector('.investors-calc__error').innerHTML = 'Введите данные для расчёта';
            document.querySelector(".investors-calc__result").classList.remove('open');
            document.querySelector('.open-calcres').style.display = 'none';
            return;
        }else if(fullArea > 0 && land <= 0 && !document.querySelector("#land").disabled){
            document.querySelector('.investors-calc__error').innerHTML = 'Введите общую площадь земельного участка';
            return;
        }else if(fullArea > 0 && (land * 100 * 40) > fullArea){
            document.querySelector('.investors-calc__error').innerHTML = 'Площадь построек должна занимать не менее 40% площади земельного участка';
            return;
        }else{
            document.querySelector('.investors-calc__error').innerHTML = '';
        }

        //document.querySelector("#full_area").value = fullArea;

        //response
        let office_cost_rent = Math.round(((offices*options.rate1) / 1000000)*100)/100,
            production_cost_rent = Math.round(((production*options.rate2) / 1000000)*100)/100,
            land_cost_rent = Math.round(((land*options.rate3) / 1000000)*100)/100,
            land_cost_buy = Math.round(((land*options.rate4) / 1000000)*100)/100,
            min_invest = (light*options.t1 + administrative*options.t2 + science*options.t3) / 1000000;

        //если отключен чекбокс ЗУ, то обнуляем расчёты по застройке
        if(document.querySelector("#land").disabled){
            min_invest = 0;
        }

        document.querySelector("#office_cost_rent").innerText = office_cost_rent;
        document.querySelector("#production_cost_rent").innerText = production_cost_rent;
        document.querySelector("#land_cost_rent").innerText = land_cost_rent;
        document.querySelector("#land_cost_buy").innerText = land_cost_buy;
        document.querySelector("#min_invest").innerText = min_invest;
        document.querySelector(".investors-calc__result").classList.add('open');

        if(office_cost_rent > 0){
            document.querySelector("#office_cost_rent").closest('.investors-calc__result-item').classList.remove('_hidden');
        }else{
            document.querySelector("#office_cost_rent").closest('.investors-calc__result-item').classList.add('_hidden');
        }
        if(production_cost_rent > 0){
            document.querySelector("#production_cost_rent").closest('.investors-calc__result-item').classList.remove('_hidden');
        }else{
            document.querySelector("#production_cost_rent").closest('.investors-calc__result-item').classList.add('_hidden');
        }
        if(land_cost_rent > 0){
            document.querySelector("#land_cost_rent").closest('.investors-calc__result-item').classList.remove('_hidden');
        }else{
            document.querySelector("#land_cost_rent").closest('.investors-calc__result-item').classList.add('_hidden');
        }
        if(land_cost_buy > 0){
            document.querySelector("#land_cost_buy").closest('.investors-calc__result-item').classList.remove('_hidden');
        }else{
            document.querySelector("#land_cost_buy").closest('.investors-calc__result-item').classList.add('_hidden');
        }
        if(min_invest > 0){
            document.querySelector("#min_invest").closest('.investors-calc__result-item').classList.remove('_hidden');
        }else{
            document.querySelector("#min_invest").closest('.investors-calc__result-item').classList.add('_hidden');
        }

        let requestString = "offices="+offices+
            "&production="+production+
            "&land="+land+
            "&light="+light+
            "&administrative="+administrative+
            "&science="+science+
            "&fullArea="+fullArea+
            "&office_cost_rent="+office_cost_rent+
            "&production_cost_rent="+production_cost_rent+
            "&land_cost_rent="+land_cost_rent+
            "&land_cost_buy="+land_cost_buy+
            "&min_invest="+min_invest;
        this.sendRequest(
            requestString,
            '/ajax/sendCalcResults.php',
            'POST',
            function(data, params){}
        );
        document.querySelector('#calcdata').value = requestString;
        document.querySelector('.open-calcres').style.display = 'block';

        //how to trigger event
        //let event = new Event("click");
        //let trigger = document.querySelector('.open-calcres');
        //trigger.dispatchEvent(event);
    },
    sendCalcForm: function(form){
        let calcdata = document.querySelector('#calcdata').value;
        let email = document.querySelector('#message-email-calc').value;
        let requestString = calcdata+'&email='+email;
        this.sendRequest(
            requestString,
            '/ajax/sendCalcResults.php',
            'POST',
            function(data, params){
                document.querySelector('.b-popup__content .calcres-form').innerText = 'Расчёт отправлен на указанный почтовый адрес';
            }
        );
    },
    decimalAdjust: function(type, value, exp) {
        // Если степень не определена, либо равна нулю...
        if (typeof exp === 'undefined' || +exp === 0) {
            return Math[type](value);
        }
        value = +value;
        exp = +exp;
        // Если значение не является числом, либо степень не является целым числом...
        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
            return NaN;
        }
        // Сдвиг разрядов
        value = value.toString().split('e');
        value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
        // Обратный сдвиг
        value = value.toString().split('e');

        //round to 0.5
        let result = 0;
        if(value[0][value[0].length - 1] <= 5 && value[0][value[0].length - 1] > 0){
            value[0] = value[0].substring(0, value[0].length-1) + '5';
            result = +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
        }else{
            result = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));
        }

        return result;
    },
    generateMap: function(){
        let mapWrapperList = document.querySelector('#map_list');

        if(!mapWrapperList)
            return;

        let mapContainer = mapWrapperList.querySelector('#first');
        let mapSettings = JSON.parse(atob(mapWrapperList.dataset.json));
        mapSettings.zoom = 10;
        mapSettings.minZoom = 8;
        mapSettings.maxZoom = 18;
        mapSettings.zoomStep = 1;
        mapSettings.controls = [];

        //console.log(mapSettings);
        ymaps.ready(function () {
            mapContainer.innerHTML = '';

            let myMap = new ymaps.Map(mapContainer, mapSettings, {suppressMapOpenBlock: true}),
                customBalloonContentLayout = ymaps.templateLayoutFactory.createClass([
                    '<ul class="map-cluster-list">',
                    '{% for geoObject in properties.geoObjects %}',
                    '<li>{{ geoObject.properties.balloonContentHeader|raw }}</li>',
                    '{% endfor %}',
                    '</ul>'
                ].join('')),
                clusterer = new ymaps.Clusterer({
                    preset: 'islands#invertedNightClusterIcons',
                    groupByCoordinates: true,
                    clusterDisableClickZoom: true,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false,
                    clusterBalloonContentLayout: customBalloonContentLayout
                }),
                getPointData = function (element) {
                    return {
                        balloonContentHeader: '<a href=' + element.link + '><strong>' + element.title + '</strong></a><br>',
                        balloonContentBody: '',
                        balloonContentFooter: '',
                        clusterCaption: ''
                    };
                },
                getPointOptions = function () {
                    return {
                        preset: 'islands#violetIcon'
                    };
                };

            let htmlMarkers = mapSettings.htmlMarkers;
            let pm;
            htmlMarkers.forEach((element) => {
                element.layout = '<div class="b-yandex-map__html-marker-2"><a href=' + element.link + '>'+element.title+'</a></div>';
                let CustomLayoutClass = ymaps.templateLayoutFactory.createClass(element.layout);
                let placemark = new ymaps.Placemark(element.coords, getPointData(element), {
                    iconLayout: CustomLayoutClass,
                    iconShape: {
                        type: 'Polygon',
                        coordinates: [element.coordShape || [0,0]]
                    },
                    cursor: 'pointer',
                    hasBalloon: false
                });

                placemark.events.add('click', function () {
                    if (!element.link) {
                        return;
                    }
                    window.location = element.link;
                });

                myMap.geoObjects.add(placemark);

                //clusterer.add(placemark);
                //pmLast = placemark;
            })

            let zoomControl = new ymaps.control.ZoomControl({
                options: {
                    size: "small",
                    position: {
                        right: 'auto',
                        left: 40,
                        top: 50,
                        bottom: 'auto'
                    }
                }
            });
            //myMap.geoObjects.add(clusterer);
            myMap.behaviors.disable('scrollZoom');
            myMap.controls.add(zoomControl);

           // let objectState = clusterer.getObjectState(pmLast);
            //clusterer.balloon.open(objectState.cluster);

        });
    },
    faqSearch: function(inputSelector){
        let _this = this;
        let input = document.querySelector(inputSelector);
        if(input){
            input.onkeyup = function(){
                _this.sendRequest(
                    "q="+this.value,
                    location.href,
                    'POST',
                    function(data, params){
                        let el = document.createElement('div');
                        el.innerHTML = data.response;
                        let newList = el.querySelector('.faq-list').innerHTML;
                        document.querySelector('.faq-list').innerHTML = newList;
                    }
                );
            }
        }
    },
    sendRequest: function(data, url, method, callback, params = []){
        let xhr = new XMLHttpRequest();
        let _this = this;
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(data);
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                callback(this, params);
            }
        }
    },
    faqSlide: function(options){
        document.addEventListener("click", function(e){
            if(!e.target.classList.contains(options.opener)) return;

            if(e.target.closest(options.parentblock).querySelector(options.openerIco)){
                e.target.closest(options.parentblock).querySelector(options.openerIco).classList.toggle(options.openerMod);
            }
            e.target.closest(options.parentblock).querySelector(options.openPosition).classList.toggle(options.closePosition);

        });
    },
    closeBanner: function(options){
        if(document.querySelector(options.closer) && document.querySelector(options.banner)){
            document.querySelectorAll(options.closer).forEach(function(item){
                item.onclick = function(){
                    let banner = this.closest(options.banner);
                    banner.style.display = 'none';
                    let bannerId = banner.dataset.id;
                    document.cookie = 'oez_banner_'+bannerId+'=close; path=/; max-age=3600';
                    return false;
                }
            });
        }
    },
    loadResident: function(){
        let _this = this;
        let popupTrigger = document.querySelectorAll('.resident-popup');
        if(popupTrigger){
            popupTrigger.forEach(function(item){
                let residentId = item.closest('.b-resident').querySelector('.resident-id').value;
                item.onclick = function(){
                    _this.sendRequest(
                        "resident="+residentId,
                        location.href,
                        'POST',
                        function(data, params){
                            let el = document.createElement('div');
                            el.innerHTML = data.response;
                            let residentData = el.querySelector('.resident-data').innerHTML;
                            document.querySelector('.resident-data').innerHTML = residentData;
                        }
                    );
                }
            });
        }
    },
    closeContacts: function(el){
        document.querySelector('.l-contacts__map-text-scroll').style.display = 'none';
        document.querySelector('.l-contacts__opener').style.display = 'block';
    },
    openContacts: function(el){
        document.querySelector('.l-contacts__map-text-scroll').style.display = 'block';
        document.querySelector('.l-contacts__opener').style.display = 'none';
    },
    nextResidentPhoto: function(el){
        let images = el.closest('.resident-popup-open__image').querySelectorAll('img');
        let visImage = 0;
        images.forEach(function(image, index){
            if(image.classList.contains('visible-image')){
                visImage = index;
            }
        });
        images[visImage].classList.remove('visible-image');
        if(images[visImage+1]){
            images[visImage+1].classList.add('visible-image');
        }else{
            images[0].classList.add('visible-image');
        }
    },
    reportCompareField: function(objectFields){
        document.addEventListener("change", function(e){
            objectFields.fieldsPairs.forEach(function(arFields){
                if(arFields.indexOf(e.target.id) < 0) return;

                let fieldGtVal = parseFloat(document.querySelector('#'+arFields[0]).value.replace(/\s+/g, ''));
                let fieldLsVal = parseFloat(document.querySelector('#'+arFields[1]).value.replace(/\s+/g, ''));

                if(fieldGtVal < fieldLsVal){
                    e.target.closest('.b-input-block').classList.add('error-field');
                    let popupError = document.createElement('div');
                    e.target.closest('.b-input-block').append(popupError);
                    popupError.classList.add('popup-error');
                    popupError.innerText = 'Проверьте данные, сумма с начала деятельности в качестве резидента должна быть больше или равна сумме с начала текущего года';

                    e.target.closest('.b-report-block').classList.add('b-report-block_status_approved__hidden');

                }else{
                    let inputBlocks = e.target.closest('.b-inputs-row').querySelectorAll('.b-input-block');
                    inputBlocks.forEach(function(inputBlock){
                        inputBlock.classList.remove('error-field');
                    });

                    let errorPopups = e.target.closest('.b-inputs-row').querySelectorAll('.popup-error');
                    if(errorPopups){
                        errorPopups.forEach(function(errorPopup){
                            errorPopup.remove();
                        });
                    }

                    let errorPopupsAll = e.target.closest('.b-report-block__body').querySelectorAll('.popup-error');

                    if(errorPopupsAll.length == 0){
                        e.target.closest('.b-report-block').classList.remove('b-report-block_status_approved__hidden');
                    }
                }
            });
        });
    },

    loadChat: function(reportId){
        let _this = this;
        BX.showWait();
        _this.sendRequest(
            'reportId='+reportId,
            '/ajax/loadChat.php',
            'POST',
            function(data, params){
                document.querySelector('#chat_messages').innerHTML = data.response;
                BX.closeWait();
            }
        );
    },

    answerChat: function(reportId){
        let _this = this;
        let answer = document.querySelector('#chat_answer').value;

        _this.sendRequest(
            'reportId='+reportId+'&sendAnswer=Y&answer='+answer,
            '/ajax/loadChat.php',
            'POST',
            function(data, params){
                _this.loadChat(reportId);
            }
        );

    },

    copyChat: function(el, targetIns){
        let _this = this;
        let text = el.dataset.id;
        let targetText = document.querySelector(targetIns).value;
        let newTargetText = text + ' ' + targetText;

        document.querySelector(targetIns).value = newTargetText;
    },

    returnReport: function(reportId){
        let _this = this;
        BX.showWait();
        _this.sendRequest(
            'reportId='+reportId,
            '/ajax/returnReport.php',
            'POST',
            function(data, params){
                let jsonData = JSON.parse(data.response);
                if (!jsonData.error) {
                    document.location = '/cabinet/report/';
                } else {
                    alert(jsonData.message);
                }
                BX.closeWait();
            }
        );
    },

    bannersInit: function() {
        let banners = document.querySelectorAll('.banner');
        setTimeout(function(){
            banners.forEach(function(item){
                item.style.display = 'block';
            });
        }, 4000);

    }
}

