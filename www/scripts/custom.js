window.onload = function(){
	sezApp.loadScrollTo();
	sezApp.generateMap();	
	sezApp.faqSearch('.faq-search__field');
	sezApp.faqSlide(
		{
			opener: 'faq-group__item-open',
			openerMod: 'faq-group__item-open_open',
			openPosition: '.faq-group__item-content',
			closePosition: 'faq-group__item-content_close',
			parentblock: '.faq-group__item'
		}
	);
	sezApp.closeBanner({
		closer: '.banner__closer',
		banner: '.banner'
	});
	Scrollbar.initAll();
	//sezApp.loadResident();
	
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
	investCalc: function(){
		let options = this.investCalcOptions;
		if(Object.keys(options).length < 1){
			console.log('Не установлены опции калькулятора');
			return;
		}
		//request
		let offices = parseInt(document.querySelector("#offices").value) > 0 ? parseInt(document.querySelector("#offices").value) : 0, 
			production = parseInt(document.querySelector("#production").value) > 0 ? parseInt(document.querySelector("#production").value) : 0, 
			land = parseInt(document.querySelector("#land").value) > 0 ? parseInt(document.querySelector("#land").value) : 0, 
			light = parseInt(document.querySelector("#light").value) > 0 ? parseInt(document.querySelector("#light").value) : 0, 
			administrative = parseInt(document.querySelector("#administrative").value) > 0 ? parseInt(document.querySelector("#administrative").value) : 0, 
			science = parseInt(document.querySelector("#science").value) > 0 ? parseInt(document.querySelector("#science").value) : 0;
		document.querySelector("#full_area").value = parseInt(light) + parseInt(administrative) + parseInt(science);

		//response
		let office_cost_rent = offices*options.rate1,
			production_cost_rent = production*options.rate2,
			land_cost_rent = land*options.rate3,
			land_cost_buy = land*options.rate4,
			min_invest = light*options.t1 + administrative*options.t2 + science*options.t3;

		document.querySelector("#office_cost_rent").innerText = office_cost_rent;
		document.querySelector("#production_cost_rent").innerText = production_cost_rent;
		document.querySelector("#land_cost_rent").innerText = land_cost_rent;
		document.querySelector("#land_cost_buy").innerText = land_cost_buy;
		document.querySelector("#min_invest").innerText = min_invest;
		document.querySelector(".investors-calc-result").style.maxHeight = "800px";
	},
	generateMap: function(){
		let mapWrapperList = document.querySelector('#map_list');

		if(!mapWrapperList)
			return;

		let mapContainer = mapWrapperList.querySelector('#first');
		let mapSettings = JSON.parse(atob(mapWrapperList.dataset.json));
		mapSettings.zoom = 9;
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

	            clusterer.add(placemark);
	            pmLast = placemark;
	        })

	        let zoomControl = new ymaps.control.ZoomControl({
		        options: {
		            size: "small",
		            position: {
		            	right: 40,
		            	left: 'auto',
		            	top: 108,
		            	bottom: 'auto'
		            }
		        }
		    });
		    myMap.geoObjects.add(clusterer);
		    myMap.behaviors.disable('scrollZoom');
		    myMap.controls.add(zoomControl);

			let objectState = clusterer.getObjectState(pmLast);
		    clusterer.balloon.open(objectState.cluster);

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
					
			e.target.classList.toggle(options.openerMod);
			e.target.closest(options.parentblock).querySelector(options.openPosition).classList.toggle(options.closePosition);
		});		
	},
	closeBanner: function(options){
		if(document.querySelector(options.closer) && document.querySelector(options.banner)){
			document.querySelector(options.closer).onclick = function(){
				this.closest(options.banner).style.display = 'none';
			}
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
							console.log(residentData);
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
	}
}

