<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Главная | РОСОЭЗ");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Главная");
?>

    <div class="l-home__main-screen j-home__main-screen">
        <div class="l-home__main-screen-video-wrapper">
            <video class="l-home__main-screen-video" width="100%" height="auto" preload="auto" autoplay="autoplay"
                   loop="loop">
                <source src="/video/movie_1920.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                <source src="/video/movie_1920.ogv" type='video/ogg; codecs="theora, vorbis"'>
                <source src="/video/movie_1920.webm" type='video/webm; codecs="vp8, vorbis"'>
            </video>
        </div>
        <? $APPLICATION->IncludeComponent(
            'kelnik:site.info',
            'social',
            array(
                "COMPONENT_TEMPLATE" => "social",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "360000"
            ),
            array()

        ); ?>
        <div class="b-main-screen-content">
            <div class="b-main-screen-text">
                <h3 class="b-main-screen-text-title-label">
                    Территория инноваций
                </h3>
                <h1>
                    <span>Особая</span> экономическая зона «Санкт — Петербург»
                </h1>
                <div class="b-main-screen-text-desc">
                    ОЭЗ «Санкт-Петербург» — это эффективный государственный проект, инновационная площадка для
                    реализации крупных бизнес-проектов по разработке и производству высокотехнологичной продукции,
                    предлагающая выгодные льготы, преференции и поддержку государства.
                </div>
            </div>
            <div class="b-main-screen__list">
                <div class="b-main-screen__item">
                    <p>
                        <strong>182,32 га</strong> земельных участков
                    </p>
                </div>
                <div class="b-main-screen__item">
                    <p>
                        <strong>50 компаний</strong> резидентов
                    </p>
                </div>
                <div class="b-main-screen__item">
                    <p>
                        <strong>50 млрд ₽</strong> выручки резидентов
                    </p>
                </div>
                <div class="b-main-screen__item">
                    <img src="/images/home/free-zone.svg" alt="free zones">
                </div>
            </div>
        </div>
    </div>

    <div class="l-home__block_bg_fixed">

        <div class="l-home__privileges l-home__block">
            <h2>Льготы и преференции</h2>
            <div class="l-home__privileges-list">
                <div class="l-home__privileges-item">
                    <div class="l-home__privileges-icon"
                         style="background-image:url('/images/home/icon-1.svg')"></div>
                    <div class="l-home__privileges-text">
                        <a href="#" class="l-home__privileges-text-title b-link-line">
                            Налоговые преференции
                        </a>
                        <p class="l-home__privileges-text-block">
                            Для резидентов установлены льготные ставки по налогам на прибыль,
                            землю, имущество и транспорт
                        </p>
                    </div>
                </div>
                <div class="l-home__privileges-item">
                    <div class="l-home__privileges-icon"
                         style="background-image:url('/images/home/icon-2.svg')"></div>
                    <div class="l-home__privileges-text">
                        <a href="#" class="l-home__privileges-text-title b-link-line">
                            Таможенные льготы
                        </a>
                        <p class="l-home__privileges-text-block">
                            Режим свободной таможенной зоны позволяет выгодно размещать высокотехнологичные
                            предприятия
                        </p>
                    </div>
                </div>
                <div class="l-home__privileges-item">
                    <div class="l-home__privileges-icon"
                         style="background-image:url('/images/home/icon-3.svg')"></div>
                    <div class="l-home__privileges-text">
                        <a href="#" class="l-home__privileges-text-title b-link-line">
                            Льготная стоимость земельного участка
                        </a>
                        <p class="l-home__privileges-text-block">
                            Плата за выкуп земельного участка составляет 25%, аренда —
                            2% от кадастровой стоимости земельного участка
                        </p>
                    </div>
                </div>
                <div class="l-home__privileges-item">
                    <div class="l-home__privileges-icon"
                         style="background-image:url('/images/home/icon-4.svg')"></div>
                    <div class="l-home__privileges-text">
                        <a href="#" class="l-home__privileges-text-title b-link-line">
                            Упрощенное взаимодействие с государством
                        </a>
                        <p class="l-home__privileges-text-block">
                            Взаимодействие с государственными регулирующими органами становится
                            максимально простым и прозрачным
                        </p>
                    </div>
                </div>
                <div class="l-home__privileges-item">
                    <div class="l-home__privileges-icon"
                         style="background-image:url('/images/home/icon-5.svg')"></div>
                    <div class="l-home__privileges-text">
                        <a href="#" class="l-home__privileges-text-title b-link-line">
                            Готовая инженерная инфраструктура
                        </a>
                        <p class="l-home__privileges-text-block">
                            Электроснабжение, теплоснабжение, водоснабжение и водоотведение,
                            газоснабжение (при технической возможности)
                        </p>
                    </div>
                </div>
                <div class="l-home__privileges-item">
                    <div class="l-home__privileges-icon"
                         style="background-image:url('/images/home/icon-6.svg')"></div>
                    <div class="l-home__privileges-text">
                        <a href="#" class="l-home__privileges-text-title b-link-line">
                            Гарантия стабильной и безопасной работы инвесторов
                        </a>
                        <p class="l-home__privileges-text-block">
                            Проект ОЭЗ на федеральном уровне курирует Министерство экономического развития
                            Российской Федерации
                        </p>
                    </div>
                </div>
            </div>

            <div class="l-home__more">
                <a href="#" class="button">Узнать больше</a>
            </div>
        </div>

        <div class="l-home__news l-home__block">
            <h2>Новости</h2>
            <div class="l-home__news-block">
                <div class="l-home__news-main">
                    <a href="#" class="l-home__news-main-img"
                       style="background-image:url('/images/home/news.jpg');"></a>
                    <div class="l-home__news-main-content">
                        <div class="l-home__news-main-title">
                            <a href="#" class="b-link-line">
                                Сегодня на площадке «Нойдорф» открылось инновационное
                                предприятие резидента ОЭЗ «Санкт-Петербург» —
                                ООО «ТР Инжиниринг»
                            </a>
                        </div>
                        <time class="l-home__news-date label-bg">
                            5 октября
                        </time>
                        <a href="#" class="l-home__news-subsection label-bg">
                            Анонс
                        </a>
                    </div>
                </div>

                <div class="l-home__news-list">
                    <div class="l-home__news-item">
                        <div class="l-home__news-title">
                            <a href="#" class="b-link-line">
                                ОЭЗ «Санкт-Петербург» впервые вошла в рейтинг лучших экономических
                                зон мира!
                            </a>
                        </div>
                        <time class="l-home__news-date label-bg">
                            5 октября
                        </time>
                        <a href="#" class="l-home__news-subsection label-bg">
                            Анонс
                        </a>
                    </div>

                    <div class="l-home__news-item">
                        <div class="l-home__news-title">
                            <a href="#" class="b-link-line">
                                Студенты факультета государственного и муниципального управления @sziu_ranepa
                                посетили площадку «Новоорловская»
                            </a>
                        </div>
                        <time class="l-home__news-date label-bg">
                            5 октября
                        </time>
                        <a href="#" class="l-home__news-subsection label-bg">
                            Анонс
                        </a>
                    </div>

                    <div class="l-home__news-item">
                        <div class="l-home__news-title">
                            <a href="#" class="b-link-line">
                                В этом месяце ОЭЗ «Санкт-Петербург» получила 1,15 млрд руб. 
                                от АО «ОЭЗ» на строительство инженерной и транспортной инфраструктуры
                            </a>
                        </div>
                        <time class="l-home__news-date label-bg">
                            5 октября
                        </time>
                        <a href="#" class="l-home__news-subsection label-bg">
                            Анонс
                        </a>
                    </div>
                </div>

                <div class="l-home__more">
                    <a href="#" class="button">Все новости</a>
                </div>

            </div>
        </div>

        <div class="l-home-resident l-home__block">
            <h2>3 шага, чтобы стать резидентом</h2>
            <div class="l-home-resident__list">
                <div class="l-home-resident__item">
                    <div class="l-home-resident__item-title">
                        Подача
                    </div>
                    <div class="l-home-resident__item-desc">
                        <div class="l-home-resident__item-desc-text">
                            Подача заявки и пакета документов в Комитет по промышленной политике
                            и инновациям Санкт — Петербурга
                        </div>
                        <span class="l-home-resident__item-desc-time">
                            40 рабочих дней
                        </span>
                    </div>
                </div>

                <div class="l-home-resident__item">
                    <div class="l-home-resident__item-title">
                        Заявление
                    </div>
                    <div class="l-home-resident__item-desc">
                        <div class="l-home-resident__item-desc-text">
                            Рассмотрение заявки на Экспертном совете ОЭЗ
                        </div>
                        <span class="l-home-resident__item-desc-time">
                            15 рабочих дней
                        </span>
                    </div>
                </div>

                <div class="l-home-resident__item">
                    <div class="l-home-resident__item-title">
                        Подписание
                    </div>
                    <div class="l-home-resident__item-desc">
                        <div class="l-home-resident__item-desc-text">
                            Подписание соглашения об осуществлении деятельности
                        </div>
                        <span class="l-home-resident__item-desc-time">
                            В течение недели
                        </span>
                    </div>
                </div>

            </div>

            <div class="l-home-resident__more-about">
                <div class="l-home-resident__more-about-text">
                    <p>
                        Стать резидентом Особой экономической зоны технико-внедренческого типа «Санкт-Петербург»
                        могут
                        как индивидуальные предприниматели, так и коммерческие организации, зарегистрированные
                        <a href="#" class="b-link-line-two">в соответствии с законодательством
                            Российской Федерации</a>
                    </p>
                </div>

                <a href="#" class="button button_theme_white">
                    Узнать больше
                </a>
            </div>
        </div>

        <div class="l-home-our-resident l-home__block">
            <h2>Резиденты</h2>
            <div class="b-carousel">
                <div class="glide j-residents-carousel">
                    <div class="glide__track b-carousel__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <li class="glide__slide">
                                <div class="b-carousel__item ">
                                    <div class="b-carousel__item-logo">
                                        <img src="/images/home/biocad.jpg" alt="BioCAD">
                                    </div>
                                    <div class="b-carousel__item-desc">
                                        <p class="b-carousel__item-title">
                                            Биокад
                                        </p>
                                        <p class="b-carousel__item-text">
                                            Фармацевтика и биотехнологии
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="glide__slide">
                                <div class="b-carousel__item ">
                                    <div class="b-carousel__item-logo">
                                        <img src="/images/home/laser-sistem.jpg" alt="BioCAD">
                                    </div>
                                    <div class="b-carousel__item-desc">
                                        <p class="b-carousel__item-title">
                                            Лазерные системы
                                        </p>
                                        <p class="b-carousel__item-text">
                                            Приборостроение
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="glide__slide">
                                <div class="b-carousel__item ">
                                    <div class="b-carousel__item-logo">
                                        <img src="/images/home/racurs.jpg" alt="BioCAD">
                                    </div>
                                    <div class="b-carousel__item-desc">
                                        <p class="b-carousel__item-title">
                                            Ракурс-инжиниринг
                                        </p>
                                        <p class="b-carousel__item-text">
                                            Микроэлектроника
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="glide__arrows b-carousel__arrows" data-glide-el="controls">
                        <button class="b-carousel__arrow b-carousel__arrow-left" data-glide-dir="<"
                                type="button"></button>
                        <button class="b-carousel__arrow b-carousel__arrow-right" data-glide-dir="&#62;"
                                type="button"></button>
                    </div>
                    <div class="b-carousel__dots" data-glide-el="controls[nav]">
                        <button class="b-carousel__dot" data-glide-dir="=0" type="button"></button>
                        <button class="b-carousel__dot" data-glide-dir="=1" type="button"></button>
                        <button class="b-carousel__dot" data-glide-dir="=2" type="button"></button>
                    </div>
                </div>
            </div>

            <div class="l-home__more">
                <a href="#" class="button">Все резиденты</a>
            </div>
        </div>

        <div class="l-home-reviews">
            <div class="b-slider-reviews">
                <div class="glide j-reviews-carousel">
                    <div class="glide__track b-carousel__track" data-glide-el="track">
                        <div class="glide__slides">

                            <div class="glide__slide">
                                <div class="b-slider-reviews__item">
                                    <div class="b-slider-reviews__item-image"
                                         style="background-image:url('/images/home/review-img.jpg')"></div>
                                    <div class="b-slider-reviews__person">
                                        <div class="b-slider-reviews__person-img">
                                            <img src="/images/home/review-person.png" alt="картинка">
                                            <div class="b-slider-reviews__person-name-wrap">
                                                <div class="b-slider-reviews__person-name">Георгий Побелянский</div>
                                                <p>Генеральный директор фармацевтической компании «ВЕРТЕКС»</p>
                                            </div>
                                        </div>
                                        <div class="b-slider-reviews__person-content">
                                            <div class="b-slider-reviews__person-text">Очевидные преимущества для
                                                резидентов ОЭЗ «Санкт-Петербург» —
                                                преференции и льготы, стоимость земельного участка ниже
                                                среднерыночной стоимости,
                                                создание инженерной инфраструктуры на территории зоны за счет
                                                городских
                                                ресурсов, продление срока действия особой экономической зоны
                                                до 2054 года.
                                            </div>
                                            <a href="#" class="b-slider-reviews__person-link b-link-line-two">
                                                Отзыв полностью
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="glide__slide">
                                <div class="b-slider-reviews__item">
                                    <div class="b-slider-reviews__item-image"
                                         style="background-image:url('/images/home/review-img.jpg')"></div>
                                    <div class="b-slider-reviews__person">
                                        <div class="b-slider-reviews__person-img">
                                            <img src="/images/home/review-person.png" alt="картинка">
                                            <div class="b-slider-reviews__person-name-wrap">
                                                <div class="b-slider-reviews__person-name">Георгий Побелянский</div>
                                                <p>Генеральный директор фармацевтической компании «ВЕРТЕКС»</p>
                                            </div>
                                        </div>
                                        <div class="b-slider-reviews__person-content">
                                            <div class="b-slider-reviews__person-text">Очевидные преимущества для
                                                резидентов ОЭЗ «Санкт-Петербург» —
                                                преференции и льготы, стоимость земельного участка ниже
                                                среднерыночной стоимости,
                                                создание инженерной инфраструктуры на территории зоны за счет
                                                городских
                                                ресурсов, продление срока действия особой экономической зоны
                                                до 2054 года.
                                            </div>
                                            <a href="#" class="b-slider-reviews__person-link b-link-line-two">
                                                Отзыв полностью
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glide__arrows b-slider-reviews__arrows" data-glide-el="controls">
                        <button class="b-slider-reviews__arrow b-slider-reviews__arrow-left" data-glide-dir="<"
                                type="button">
                            <img src="/images/home/slide-arrow-white.svg" alt="стрелка влево">
                        </button>
                        <button class="b-slider-reviews__arrow b-slider-reviews__arrow-right" data-glide-dir="&#62;"
                                type="button">
                            <img src="/images/home/slide-arrow-white.svg" alt="стрелка вправо">
                        </button>
                    </div>

                    <div class="b-slider-reviews__dots" data-glide-el="controls[nav]">
                        <button class="b-slider-reviews__dot" data-glide-dir="=0" type="button"></button>
                        <button class="b-slider-reviews__dot" data-glide-dir="=1" type="button"></button>
                    </div>

                </div>
            </div>
        </div>

        <div class="l-home-plots">
            <div class="l-home-plots__title-mobile">
                Расположение зон
            </div>
            <div class="l-home-plots__list">

                <div class="l-home-plots__item">

                    <div class="l-home-plots__map l-home-plots__map-neudorf">
                        <div class="l-home-plots__all-map-images">
                            <div class="l-home-plots__all-map-icon-wrap">
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip">
                                Новоорловская
                            </span>
                                </a>
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip">
                                Нойдорф
                            </span>
                                </a>
                                <div class="l-home-plots__all-map-icon icon-port">
                                    <div class="b-tooltip">
                                        Порт
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                                    <div class="b-tooltip">
                                        Вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-center-city">
                                    <div class="b-tooltip">
                                        Центр города
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                                    <div class="b-tooltip">
                                        Ладожский вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-airport">
                                    <div class="b-tooltip">
                                        Аэропорт
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="l-home-plots__content">
                        <div class="l-home-plots__title">
                            <a href="#" class="b-link-line">
                                Участок «Нойдорф»
                            </a>
                        </div>
                        <div class="l-home-plots__desc">
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>18,99 га</strong> <span>площадь участка</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>30 км</strong> <span>от центра</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>Стрельна</strong> <span>юго — запад Петербурга</span>
                                </p>
                            </div>
                        </div>
                        <div class="l-home-plots__text">
                            <p>
                                В окружении развитой городской инфраструктуры и культурного наследия. Менее
                                10 минут до выезда на КАД. 20 минут на автотранспорте до аэропорта
                            </p>
                        </div>
                        <div class="l-home-plots__link">
                            <a href="#" class="b-link-line">
                                Узнать больше
                            </a>
                        </div>
                    </div>
                </div>

                <div class="l-home-plots__item">

                    <div class="l-home-plots__map l-home-plots__map-novoorlovskaya">
                        <div class="l-home-plots__all-map-images">
                            <div class="l-home-plots__all-map-icon-wrap">
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-one">
                            <span class="b-tooltip">
                                Новоорловская
                            </span>
                                </a>
                                <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-two">
                            <span class="b-tooltip">
                                Нойдорф
                            </span>
                                </a>
                                <div class="l-home-plots__all-map-icon icon-port">
                                    <div class="b-tooltip">
                                        Порт
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                                    <div class="b-tooltip">
                                        Вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-center-city">
                                    <div class="b-tooltip">
                                        Центр города
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                                    <div class="b-tooltip">
                                        Ладожский вокзал
                                    </div>
                                </div>

                                <div class="l-home-plots__all-map-icon icon-airport">
                                    <div class="b-tooltip">
                                        Аэропорт
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="l-home-plots__content">
                        <div class="l-home-plots__title">
                            <a href="#" class="b-link-line">
                                Участок «Новоорловская»
                            </a>
                        </div>
                        <div class="l-home-plots__desc">
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>163,33 га</strong> <span>площадь участка</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>20 км</strong> <span>от центра</span>
                                </p>
                            </div>
                            <div class="l-home-plots__desc-item">
                                <p>
                                    <strong>Приморский район</strong> <span>В лесопарке «Новоорловский»</span>
                                </p>
                            </div>
                        </div>
                        <div class="l-home-plots__text">
                            <p>
                                Развитая транспортная инфраструктура. Менее 10 минут до выезда на КАД.
                                30 минут до центра города на автотранспорте. 20 минут
                                до метро на автотранспорте
                            </p>
                        </div>
                        <div class="l-home-plots__link">
                            <a href="#" class="b-link-line">
                                Узнать больше
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="l-home-plots__all-map">
                <div class="l-home-plots__all-map-images">
                    <div class="l-home-plots__all-map-icon-wrap">
                        <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-novoorlovskaya">
                            <span class="b-tooltip">
                                Новоорловская
                            </span>
                        </a>
                        <a href="#" class="l-home-plots__all-map-build l-home-plots__all-map-build-neudorf">
                            <span class="b-tooltip">
                                Нойдорф
                            </span>
                        </a>
                        <div class="l-home-plots__all-map-icon icon-port">
                            <div class="b-tooltip">
                                Порт
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-one">
                            <div class="b-tooltip">
                                Вокзал
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-center-city">
                            <div class="b-tooltip">
                                Центр города
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-rialway-station icon-rialway-station-two">
                            <div class="b-tooltip">
                                Ладожский вокзал
                            </div>
                        </div>

                        <div class="l-home-plots__all-map-icon icon-airport">
                            <div class="b-tooltip">
                                Аэропорт
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="l-home-partners l-home__block">
            <h2>Партнеры</h2>

            <div class="b-carousel b-carousel_theme_partners">
                <div class="glide j-partners-carousel">
                    <div class="glide__track b-carousel__track" data-glide-el="track">
                        <ul class="glide__slides">
                            <li class="glide__slide">
                                <div class="b-carousel__item b-carousel__item_without_description">
                                    <div class="b-carousel__item-logo">
                                        <img src="/images/home/partner1.png"
                                             alt="Ассоциация клатеров и технопарков России">
                                    </div>
                                </div>
                            </li>
                            <li class="glide__slide">
                                <div class="b-carousel__item b-carousel__item_without_description">
                                    <div class="b-carousel__item-logo">
                                        <img src="/images/home/partner2.png"
                                             alt="Санкт-Петербургская торогво-промышленная палата">
                                    </div>
                                </div>
                            </li>
                            <li class="glide__slide">
                                <div class="b-carousel__item b-carousel__item_without_description">
                                    <div class="b-carousel__item-logo">
                                        <img src="/images/home/partner3.png"
                                             alt="Машиностроительный кластер Республики Татарстан">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="glide__arrows b-carousel__arrows" data-glide-el="controls">
                        <button class="b-carousel__arrow b-carousel__arrow-left" data-glide-dir="<"
                                type="button"></button>
                        <button class="b-carousel__arrow b-carousel__arrow-right" data-glide-dir="&#62;"
                                type="button"></button>
                    </div>
                    <div class="b-carousel__dots" data-glide-el="controls[nav]">
                        <button class="b-carousel__dot" data-glide-dir="=0" type="button"></button>
                        <button class="b-carousel__dot" data-glide-dir="=1" type="button"></button>
                        <button class="b-carousel__dot" data-glide-dir="=2" type="button"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>