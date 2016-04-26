$(function () {
	
    // Vue.config.debug = true;
	
    var vm = new Vue({
        el: 'body',
        data: {
            curEvent: curEvent,
            stands: stands,
            selected: selected,
            curStep: 1,
            steps: steps,
            services: services,
            options: options,
            standNum: 0,
            pavillion: 0,
            sketch: {
                objects: {}
            },
            order: order,
            selectedParams: selectedParams,
            curLang: curLang,
            userData: {
                password: '',
                password_confirm: '',
                companyName: '',
                companyAddress: '',
                name: '',
                lastName: ''
            },
            orderDesc: '',
            agreement: false,
            guest_agreement: false,
            colors: colors,
            allServices: allServices,
            extents: extents,
			vat: vat
        },

        methods: {
            setSelected: function (id, index) {
                if (this.selectedStand.ID == 0) {
                    this.showStandTypePopup(index);
                }
                this.selected = id;
            },
            showStandTypePopup: function (index) {
                $(".standsTypes__window").eq(index).show();
            },
            setType: function (type, index) {
                this.selectedParams.TYPE = type.form.standtype.value;
                $(".standsTypes__window").eq(index).hide();
            },
            nextStep: function () {
                if (this.curStep == 1 && this.selectedStand.ID == 0) {
                    this.setStep(3);
                } else if (this.curStep == 3 && this.selectedStand.ID == 0) {
                    this.setStep(5);
                } else {
                    this.setStep(parseInt(this.curStep) + 1);
                }
                    destination = $('.main').offset().top -100;
                    $("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 700);
            },
            prevStep: function () {
                if (this.curStep > 1) {
                    if (this.curStep == 2 && this.selectedStand.ID == 0) {
                        this.setStep(1);
                    } else {
                        this.setStep(parseInt(this.curStep) - 1);
                    }
                }
            },
            validateStep: function (step) {
                var valid = false;
                switch (parseInt(step)) {
                    case 1:
                        if (this.selectedStand) {
                            valid = true;
                        }
                        break;
                    case 2:
                        valid = true;
                        break;
                    case 3:
                        valid = true;
                        break;
                    case 4:
                        if (this.selectedStand.ID == 0) {
                            valid = true;
                            break;
                        } else {
                            if (typeof itemsForSketch == 'undefined') {
                                valid = false;
                                break;
                            }
                            this.sketch = ru.octasoft.oem.designer.Main.getScene();
                            var equipmentCount;
                            equipmentCount = this.selectedStand.EQUIPMENT.reduce(function (sum, eq) {
                                return sum += parseInt(eq.QUANTITY);
                            }, 0);

                            var optionsCount = 0;
                            $.each(this.optionsForSketch, function (id, option) {
                                if (typeof option == 'object' && option.hasOwnProperty('QUANTITY')) {
                                    optionsCount += option.QUANTITY;
                                }
                            });

                            if (
                                this.sketch.hasOwnProperty('objects')
                                &&
                                this.sketch.objects.length == equipmentCount + optionsCount
                            ) {
                                valid = true;
                                break;
                            } else {
                                $('#modalSketchError').arcticmodal();
                            }
                        }
						break;
                }

                return valid;
            },
            setStep: function (step) {
                if (this.curStep > step || this.validateStep(step - 1)) {
                    if (!(this.selectedStand.ID == 0 && (step == 2 || step == 4))) {
                        top.BX.ajax.history.put({}, window.location.search.replace(/&*step=[\d]/, "") + '&step=' + step);
                        this.curStep = step;

                        $('.js-step-watcher').val(step);

                        $(".headersection__languagedropdownbutton").each(function () {
                            if (this.href.match(/step=[\d]/)) {
                                this.href = this.href.replace(/step=[\d]/, 'step=' + step);
                            } else {
                                this.href = this.href + '&step=' + step;
                            }
                        });
                    }
                }
            },
            incEqQty: function (eq) {
                eq.QUANTITY++;
            },
            decEqQty: function (eq) {
                if (eq.QUANTITY > 1) {
					eq.QUANTITY--;
				}
            },
            getServices: function (successCallback) {
                var self = this;
                $.post('/local/components/wolk/event.detail/ajax.php', {
                    sessid: BX.bitrix_sessid(),
                    action: 'getServices',
                    event: this.curEvent.ID
                }).done(function (data) {
                    self.services = data[11];
                    self.options  = data[10];
                    Vue.nextTick(function () {
                        $('.styler').trigger('refresh');
                    });
                    if (typeof successCallback == 'function') {
                        successCallback()
                    }
                });
            },
            placeOrder: function (type) {
                $('#js-place-order-id').attr("disabled", true);
				var image, that = this;
				
				var getSketchImage = function() {
					image = ru.octasoft.oem.designer.Main.saveJPG();
				}
				
				$.when(getSketchImage()).done(function() {
					$.ajax({
						url: '/local/components/wolk/event.detail/ajax.php',
						type: 'post',
						data: {
							sessid: BX.bitrix_sessid(),
							action: 'placeOrder',
							event: that.curEvent.ID,
							stand: JSON.stringify({
								ID: that.selectedStand.ID,
								NAME: that.selectedStand.NAME
							}),
							equipment: JSON.stringify(that.selectedStand.EQUIPMENT.map(function(val) {
								return {
									'ID': val.ID,
									'COUNT': val.COUNT
								};
							})),
							options: JSON.stringify(that.selectedStand.OPTIONS),
							services: JSON.stringify(that.selectedStand.SERVICES),
							selectedParams: JSON.stringify(that.selectedParams),
							orderParams: {
								standNum: that.standNum,
								pavillion: that.pavillion,
								sketch: JSON.stringify(that.sketch),
								eventId: that.curEvent.ID,
								eventName: that.curEvent.NAME,
								width: that.selectedParams.WIDTH,
								depth: that.selectedParams.DEPTH,
								standType: that.selectedParams.TYPE,
								SKETCH_IMAGE: image
							},
							orderDesc: that.orderDesc,
							userData: JSON.stringify(that.userData),
							orderId: that.order ? that.order.ID : '',
							placeType: type
						},
						dataType: 'json',
						success: function(data) {
							$('#modalSuccessOrder').arcticmodal({
								closeOnOverlayClick: false
							});
							$('#js-place-order-id').attr("disabled", false);
							sessionStorage.clear();
						},
						error: function (data) {
							$('.errortext:visible').html(data.responseText);
							$('#js-place-order-id').attr("disabled", false)
						}
					});
				});
            },
            deleteServiceItem: function (cartSectionName, index) {
                $("input#"+cartSectionName+"_"+index).val(0);
                var self = this,
                    i = 1;
                $.each(this.selectedStand.SERVICES, function (sectionId, items) {
                    i = 0;
                    $.each(items, function (itemIndex, item) {
                        if (item.CART_SECTION.NAME == cartSectionName && index == i) {
                            Vue.delete(self.selectedStand.SERVICES[sectionId], itemIndex);
                        }
                        ++i;
                    })
                });
            },
            deleteOption: function (sectionId, item) {
                $("input#"+sectionId+"_"+item.ID).val(0);
                Vue.delete(this.selectedStand.OPTIONS[sectionId], item.ID);
            },
            toggleSectionVisible: function (section) {
                if (section.hasOwnProperty('visible')) {
                    Vue.set(section, 'visible', !section.visible);
                } else {
                    Vue.set(section, 'visible', false);
                }
            },
            isEmptyObject: function (object) {
                var name;
                for (name in object) {
                    return false;
                }
                return true;
            }
        },
        computed: {
            allPrices: function () {
                return this.curEvent.ALL_PRICES
            },
            itemsForSketch: {
                cache: false,
                get: function () {
                    var result = [];

                    this.selectedStand.EQUIPMENT.forEach(function (eq) {
                        if (eq.WIDTH && eq.HEIGHT) {
                            result.push({
                                title: eq.NAME,
                                quantity: parseInt(eq.QUANTITY),
                                type: eq.SKETCH_TYPE || 'droppable',
                                w: eq.WIDTH,
                                h: eq.HEIGHT,
                                id: eq.ID,
                                imagePath: eq.SKETCH_IMAGE || '/i.php?src=/local/templates/.default/build/images/noimage.jpg&w=50&h=50'
                            });
                        }
                    });

                    var exists = false;
                    this.optionsForSketch.forEach(function (eq, key) {
                        exists = false;
                        var newItem = {
                            title: eq.NAME,
                            quantity: parseInt(eq.QUANTITY),
                            type: eq.SKETCH_TYPE || 'droppable',
                            w: eq.WIDTH,
                            h: eq.HEIGHT,
                            id: eq.ID,
                            imagePath: eq.SKETCH_IMAGE || '/i.php?src=/local/templates/.default/build/images/noimage.jpg&w=50&h=50'
                        };

                        result.forEach(function (existingEq, key) {
                            if (existingEq.id == newItem.id) {
                                exists = true;
                                result[key].quantity += parseInt(newItem.quantity);
                            }
                        });

                        if (!exists && newItem.w && newItem.h) {
                            result.push(newItem);
                        }
                    });

                    return result;
                }
            },
            selectedStand: {
                cache: false,
                get: function () {
                    return this.stands[this.selected]
                },
                set: function (val) {
                    this.selected = val.ID;
                    this.stands[this.selected] = val;
                }
            },
			totalPrice: function () {
				if (this.selectedStand) {
					return parseFloat((this.totalSurchargePrice + this.taxPrice).toFixed(2));
				}
				return null;
			},
			taxPrice: function () {
				if (this.selectedStand) {
					return parseFloat((this.totalSurchargePrice / 100 * this.vat).toFixed(2));
				}
				return null;
			},
			hasMargins: function () {
                return (this.curEvent.MARGIN_DATES !== null);
            },
            moneySurcharge: function () {
                return parseFloat((this.curEvent.SURCHARGE * this.summaryPrice / 100).toFixed(2));
            },
            totalSurchargePrice: function () {
                return parseFloat((this.summaryPrice + this.moneySurcharge).toFixed(2));
            },
            summaryPrice: function () {
                if (this.selectedStand) {
                    var price = this.selectedStand.PRICE.PRICE,
                        self = this;
                    if (this.selectedStand.EQUIPMENT.length > 0) {
                        price = this.selectedStand.EQUIPMENT.reduce(function (sum, eq) {
                            if (eq.QUANTITY > eq.COUNT) {
                                return parseFloat(sum + parseFloat(self.allServices[eq.ID].PRICE * (eq.QUANTITY - eq.COUNT)));
                            } else {
                                return sum;
                            }
                        }, price);
                    }
                    if (!$.isEmptyObject(this.selectedStand.SERVICES)) {
                        $.each(this.selectedServices, function (serviceId, service) {
                            if (service.PRICE && parseFloat(service.PRICE) > 0 && service.QUANTITY) {
                                if (service.MULTIPLIER) {
                                    service.PRICE *= service.MULTIPLIER;
                                }
                                price += parseFloat(self.allServices[service.ID].PRICE * service.QUANTITY);
                            }
                        });
                    }
                    if (!$.isEmptyObject(this.selectedStand.OPTIONS)) {
                        $.each(this.selectedStand.OPTIONS, function (groupId, options) {
                            $.each(options, function (optionId, option) {
                                price += parseFloat(self.allServices[option.ID].PRICE * option.QUANTITY);
                            })
                        });
                    }
                    return parseFloat(price.toFixed(2)) || 0;
                }
                return null;
            },
            selectedServices: {
                cache: false,
                get: function () {
                    var res = [];
                    if (!$.isEmptyObject(this.selectedStand.SERVICES)) {
                        $.each(this.selectedStand.SERVICES, function (groupId, services) {
                            $.each(services, function (serviceId, service) {
                                res.push(service);
                            });
                        });
                    }
                    return res;
                }
            },
            groupedSelectedServices: {
                cache: false,
                get: function () {
                    var res = {};
                    $.each(this.selectedServices, function (id, service) {
                        if (service.CART_SECTION == undefined) {
                            service.CART_SECTION = {};
                            service.CART_SECTION.NAME = '';
                        }
                        if (!res.hasOwnProperty(service.CART_SECTION.NAME)) {
                            res[service.CART_SECTION.NAME] = [];
                        }
                        res[service.CART_SECTION.NAME].push(service);
                    });

                    return res;
                }
            },
            optionsForSketch: {
                cache: false,
                get: function () {
                    allOptions = {};
                    res = [];
                    if (!$.isEmptyObject(this.options)) {
                        $.each(this.options.SECTIONS, function (groupId, group) {
                            if (group.hasOwnProperty('ITEMS')) {
                                $.each(group.ITEMS, function (optionId, option) {
                                    allServices[option.ID] = option;
                                })
                            }
                        });
                    }

                    if (!$.isEmptyObject(this.selectedStand.OPTIONS)) {
                        $.each(this.selectedStand.OPTIONS, function (groupId, options) {
                            $.each(options, function (optionId, option) {
                                allServices[option.ID].QUANTITY = option.QUANTITY;
                                res.push(allServices[option.ID]);
                            })
                        });
                    }

                    return res;
                }
            },
            regValidation: {
                cache: false,
                get: function () {
                    return {
                        password: (this.userData.password.length >= 6),
                        password_confirm: (this.userData.password_confirm == this.userData.password),
                        companyName: (this.userData.companyName.length > 0),
                        companyAddress: (this.userData.companyAddress.length > 0),
                        name: (this.name.length > 0),
                        lastName: (this.lastName.length > 0)
                    }
                }
            },
            regFormValid: function () {
                var validation = this.regValidation;
                Object.keys(validation).every(function (key) {
                    return validation[key];
                });
            },
            currency_format: function () {
                return this.curEvent.CURRENCY.FORMAT;
            }
        },
        watch: {
            'selectedStand': {
                handler: function (val, oldVal) {
                    $(document.body).trigger("sticky_kit:recalc");
                    var prevVal = JSON.parse(sessionStorage.getItem(this.curEvent.ID));
                    sessionStorage.setItem(this.curEvent.ID, JSON.stringify(
                        {
                            selectedStand: val,
                            sketch: prevVal && prevVal.sketch ? prevVal.sketch : null
                        }
                    ));
                },
                deep: true
            },
            'selectedStand.SERVICES' : {
                handler: function (val, oldVal) {

                },
                deep: true
            },
            'sketch': {
                handler: function (val, oldVal) {
                    var prevVal = JSON.parse(sessionStorage.getItem(this.curEvent.ID));
                    sessionStorage.setItem(this.curEvent.ID, JSON.stringify({
						selectedStand: prevVal && prevVal.selectedStand ? prevVal.selectedStand : null,
						sketch: val
                    }));
                },
                deep: true
            },
            'curStep': function (val, oldVal) {
                if (val > 1) {
                    Vue.nextTick(function () {
                        $('.styler').trigger('refresh');
                    });
                    if (val < 4) {
                        Vue.nextTick(function () {
                            $("[data-sticky_column]").stick_in_parent({
                                parent: "[data-sticky_parent]"
                            });
                        });
                    }
                }
                if (val >= 2) {
                    if ($.isEmptyObject(this.services)) {
                        var self = this;
                        Vue.nextTick(function () {
                            self.getServices();
                        });
                        this.$root.$children.forEach(function (val, key) {
                            if (val.hasOwnProperty('toggleVisible')) {
                                val.hide();
                            }
                        });
                    }
                }
                if (val == 3) {
                    this.$root.$children.forEach(function (val, key) {
                        if (val.hasOwnProperty('toggleVisible')) {
                            val.hide();
                        }
                    });
                    Vue.nextTick(function () {
                        $('.serviceContainer div[data-module="pagesubtitle-dropdown"]:first').not('.open').addClass('open');
                    })
                } else if (val == 2) {
                    Vue.nextTick(function () {
                        $('.equipmentcontainer div[data-module="pagesubtitle-dropdown"]:first').not('.open').addClass('open');
                    })
                } else if (val == 4) {
                    var self = this;
                    var curItems = null;
                    if (self.sketch) {
                        curItems = self.sketch.objects;
                    }
                    if (self.selectedParams.hasOwnProperty('SKETCH')) {
                        curItems = self.selectedParams.SKETCH.objects;
                    }
                    window.resizeEditor = function (items) {
                        var editorH = Math.max(120 + (items.length * 135), 675);
                        $("#designer").height(editorH);
                        var firstRun = !itemsForSketch;
                        window.editorScrollTop = $("#designer").offset().top - 30;
                        window.editorScrollBottom = window.editorScrollTop - 30 + editorH - $(window).height();
                        if (window.editorScrollBottom < window.editorScrollTop) window.editorScrollTop = window.editorScrollBottom;
                        if (!firstRun) {
                            ru.octasoft.oem.designer.Main.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
                            
							// trigger resize event to update layout with new height
                            if (Event.prototype.initEvent) {
								
                                // Для IE.
                                var evt = window.document.createEvent('UIEvents');
                                evt.initUIEvent('resize', true, false, window, 0);
                                window.dispatchEvent(evt);
                            } else {
                                window.dispatchEvent(new Event('resize'));
                            }
                        }
                    };
                    if (typeof itemsForSketch === 'undefined') {
                        // $('head').append('<script src="/local/templates/.default/javascripts/designer.js"></script>');
                        itemsForSketch = self.itemsForSketch;
                        window.addEventListener("touchmove", function (event) {
                            event.preventDefault();
                        }, false);
                        if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
                            var meta = document.getElementById("viewport");
                            meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
                        }
                        Vue.nextTick(function () {
                            window.onEditorReady = function () {
                                $(window).on("scroll", function (e) {
                                    ru.octasoft.oem.designer.Main.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
                                });
                                ru.octasoft.oem.designer.Main.init({
									language: {
										rightColumnLabel: langs.equipment,
										ordered: langs.ordered,
										placed: langs.placed,
										shelfPopupLabel: ""
									},
                                    w: self.selectedParams.WIDTH,
                                    h: self.selectedParams.DEPTH,
									
                                    // row corner head island
                                    type: self.selectedParams.TYPE || "row",
                                    items: itemsForSketch,
                                    placedItems: curItems || {}
                                });
                                setTimeout(function() { window.resizeEditor(itemsForSketch); }, 100);
                            };
                            lime.embed("designer", 0, 0, '', '/');
                        });
                    } else {
                        if (JSON.stringify(itemsForSketch) != JSON.stringify(self.itemsForSketch)) {
                            itemsForSketch = self.itemsForSketch;
                            Vue.nextTick(function () {
                                ru.octasoft.oem.designer.Main.init({
									language: {
										rightColumnLabel: langs.equipment,
										ordered: langs.ordered,
										placed: langs.placed,
										shelfPopupLabel: ""
									},
                                    w: self.selectedParams.WIDTH,
                                    h: self.selectedParams.DEPTH,
                                    
									// row corner head island
                                    type: self.selectedParams.TYPE || "row",
                                    items: itemsForSketch,
                                    placedItems: curItems || {}
                                });
                            });
                            setTimeout(function() { window.resizeEditor(itemsForSketch); }, 300);
                        }
                    }
                }
            }
        },
        ready: function () {
            if (this.order) {
                sessionStorage.clear();
                this.standNum = this.order.PROPS.standNum.VALUE;
                this.pavillion = this.order.PROPS.pavillion.VALUE;
            } else {
                if (sessionStorage.getItem(curEvent.ID)) {
                    var eventData = JSON.parse(sessionStorage.getItem(curEvent.ID));
                    this.selectedStand = eventData.selectedStand;
                    this.sketch = eventData.sketch;
                    if (eventData.hasOwnProperty('sketch')) {
                        this.sketch = eventData.sketch
                    }
                }
            }
        }
    });

    $(window).on('popstate', function (event) {
        if (event.state) {
            vm.prevStep();
        }
    });

    $("#email_confirm, #comMail").inputmask({
        mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
        greedy: false
    });

    $(document).on('click', '.photoZoom', function () {
        var photo = $('<div class="photoModal" />');
        photo.html('<img src="' + $(this).attr("href") + '">');
        photo.prepend('<div class="arcticmodal-close modalClose"></div>');
        $.arcticmodal({
            content: photo
        });
        return false
    });

    var hash = window.location.search,
        found = false;
    if (found = hash.match(/step=([1-5])/)) {
        vm.setStep(found[1] == 5 ? 4 : found[1]);
    }
});

// Изменение количества товарных позиций.
var quantityMixin = {
    methods: {
        incQty: function (item) {
            item.QUANTITY++;
        },
        decQty: function (item) {
            if (item.QUANTITY > 0) {
                item.QUANTITY--;
            }
        }
    }
};

// Добавление в корзину.
var addToCartMixin = {
    methods: {
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.section.NAME + ', ' + self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.section.ID, NAME: self.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: parseFloat(self.items[item.ID].PRICE).toFixed(2)
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            });
        }
    }
};

// Переключение видимости.
var toggleableMixin = {
    methods: {
        toggleVisible: function () {
            this.visible = !this.visible;
            if (this.visible) {
                Vue.nextTick(function () {
                    $('.styler').trigger('refresh');
                });
            }
        },
        hide: function () {
            this.visible = false;
            Vue.nextTick(function () {
                $('.styler').trigger('refresh');
            });
        }
    }
};

// Формат валюты.
var currencyMixin = {
    computed: {
        currency_format: function () {
            return this.$root.curEvent.CURRENCY.FORMAT
        }
    }
};


// Компонент: "Электрика и коммуникации".
Vue.component('electrics-and-communications', {
    template: '#electrics-and-communications',
	/*
    methods: {
        save: function () {
            this.$children.forEach(function (comp) {
                comp.addToCart();
            })
        }
    },
	*/
	parent: function() {
		return this;
	},
    data: function () {
        return {
            sectionId: 1,
            visible: true
        }
    },
    computed: {
        section: function () {
            return this.$parent.services.SECTIONS[this.sectionId];
        },
        sections: function () {
            return this.section.SECTIONS;
        }
    },
    components: {
        'electrics-and-communications-item': {
            template: '#electrics-and-communications-item',
            data: function () {
                return {
                    selectedItems: [
                        {
                            ID: '',
                            QUANTITY: 0
                        }
                    ]
                }
            },
            props: ['section'],
            computed: {
                items: function () {
                    return this.section.ITEMS;
                }
            },
            methods: {
                addItem: function () {
                    this.selectedItems.push({
                        ID: '',
                        QUANTITY: 0
                    })
                },
				incQty: function (selectedItem, index) {
					if (selectedItem.ID > 0) {
						selectedItem.QUANTITY++;
						this.addToCart(selectedItem, index);
					}
				},
				decQty: function (selectedItem, index) {
					if (selectedItem.ID > 0) {
						if (selectedItem.QUANTITY > 0) {
							selectedItem.QUANTITY--;
							this.addToCart(selectedItem, index);
						}
					}
				},
				addToCart: function(selectedItem, index) {
					if (selectedItem.QUANTITY > 0) {
						var item = this.items[parseInt(selectedItem.ID)];
						
						this.$root.$set('selectedStand.SERVICES[' + this.section.ID + '][' + index + ']', {
							ID: item.ID,
							NAME: item.NAME,
                            CART_SECTION: {ID: this.section.ID, NAME: this.section.NAME},
							PRICE: parseFloat(item.PRICE).toFixed(2),
							QUANTITY: selectedItem.QUANTITY
						});
					} else {
						Vue.delete(this.$root.selectedStand.SERVICES[this.section.ID], index);
					}					
				}
            },
            ready: function () {
                if (
                    this.$root.selectedStand
                    &&
                    this.$root.selectedStand.hasOwnProperty('SERVICES')
                    &&
                    this.$root.selectedStand.SERVICES
                    &&
                    this.$root.selectedStand.SERVICES.hasOwnProperty(this.section.ID)
                ) {
                    this.selectedItems = this.$root.selectedStand.SERVICES[this.section.ID];
                }
            },
            mixins: [
                // quantityMixin,
                // addToCartMixin,
                currencyMixin
            ]
        }
    },
    mixins: [toggleableMixin]
});


// Компонент: "Графика".
Vue.component('graphics', {
    template: '#graphics',
    data: function () {
        return {
            sectionId: 2,
            visible: false
        }
    },
    computed: {
        section: function () {
            return this.$parent.services.SECTIONS[this.sectionId];
        },
        sections: function () {
            return this.section.SECTIONS;
        },
        components: function () {
            return this.$children;
        }
    },
    methods: {
        save: function () {
            this.components.forEach(function (component) {
                component.addToCart();
            })
        },
        incQty: function (section) {
            section.qty++;
        },
        decQty: function (section) {
            if (section.qty > 0) {
                section.qty--;
            }
        }
    },
    components: {
        'fascia-name': {
            template: '#fascia-name',
            data: function () {
                return {
                    sectionId: 12,
                    itemId: 5,
                    text: '',
                    selectedColor: ''
                }
            },
            ready: function () {
                if (
                    this.$root.order
                    &&
                    this.$root.order.selectedStand.hasOwnProperty('SERVICES')
                    &&
                    this.$root.order.selectedStand.SERVICES
                    &&
                    this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
                ) {
                    this.text = this.$root.order.selectedStand.SERVICES[this.sectionId][0].FASCIA_TEXT;
                    this.selectedColor = this.$root.order.selectedStand.SERVICES[this.sectionId][0].FASCIA_COLOR;
                }
            },
            computed: {
                section: function () {
                    return this.$parent.sections[this.sectionId];
                },
                item: function () {
                    return this.section.ITEMS[this.itemId];
                },
                price: function () {
                    return parseFloat(this.item.PRICE).toFixed(2);
                },
                colors: function () {
                    var result = [],
                        self = this;
                    if (this.item.PROPERTY_27.length > 0) {
                        this.item.PROPERTY_27.forEach(function (val, key) {
                            if (self.$root.colors.hasOwnProperty(val)) {
                                result.push(self.$root.colors[val]);
                            }
                        });
                    }

                    return result;
                },
                allColors: function () {
                    return this.$root.colors;
                }
            },
            methods: {
                selectColor: function (color) {
                    this.selectedColor = color;
                    $.arcticmodal('close');
                },
                getTotalPrice: function () {
                    return this.price;
                },
                addToCart: function () {
                    var self = this;
                    if (self.text && self.selectedColor) {
                        self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][0]', {
                            ID: self.item.ID,
                            NAME: self.item.NAME + ' (' + self.allColors[self.selectedColor].UF_NUM + ' ' + self.text + ')',
                            CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                            QUANTITY: 1,
                            FASCIA_TEXT: self.text,
                            FASCIA_COLOR: self.selectedColor,
                            PRICE: self.price,
                            PROPS: [
                                {
                                    NAME: 'Текст',
                                    CODE: 'FASCIA_TEXT',
                                    VALUE: self.text
                                },
                                {
                                    NAME: 'Цвет',
                                    CODE: 'FASCIA_COLOR',
                                    VALUE: self.selectedColor
                                }
                            ]
                        });
                    }
                }
            },
            mixins: [currencyMixin]
        },
        'logo': {
            template: '#logo',
            data: function () {
                return {
                    sectionId: 13,
                    itemId: 10,
                    logotypes: [
                        {
                            EXTENT: '',
                            QUANTITY: 0,
                            FILE: null,
                            COMMENTS: ''
                        }
                    ]
                }
            },
            ready: function () {
                if (
                    this.$root.order
                    &&
                    this.$root.order.selectedStand.hasOwnProperty('SERVICES')
                    &&
                    this.$root.order.selectedStand.SERVICES
                    &&
                    this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
                ) {
                    this.logotypes = this.$root.order.selectedStand.SERVICES[this.sectionId];
                }
            },
            computed: {
                section: function () {
                    return this.$parent.sections[this.sectionId];
                },
                item: function () {
                    return this.section.ITEMS[this.itemId];
                },
                price: function () {
                    return this.item.PRICE;
                },
                extents: function () {
                	var res = {};
                	for(extentIndex in this.item.PROPERTY_39) {
                		if(this.$root.extents.hasOwnProperty(this.item.PROPERTY_39[extentIndex])) {
                			res[this.item.PROPERTY_39[extentIndex]] = this.$root.extents[this.item.PROPERTY_39[extentIndex]].NAME;
                		}
                	}
                
                    return res;
                }
            },
            methods: {
                incQty: function (logotype) {
                    logotype.QUANTITY++;
                },
                decQty: function (logotype) {
                    if (logotype.QUANTITY > 0) {
                        logotype.QUANTITY--;
                    }
                },
                addLogo: function () {
                    this.logotypes.push({
                        EXTENT: '',
                        QUANTITY: 0,
                        FILE: null,
                        COMMENTS: ''
                    })
                },
                getTotalPrice: function () {
                    var self = this;
                    return this.logotypes.reduce(function (sum, item) {
                        return sum + parseInt(self.price * item.QUANTITY);
                    }, 0)
                },
                addToCart: function () {
                    var self = this;
                    this.logotypes.forEach(function (item, index) {
                        if (item.QUANTITY > 0) {
                            self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                                ID: self.item.ID,
                                NAME: self.item.NAME,
                                CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                                QUANTITY: item.QUANTITY,
                                PRICE: self.price,
                                PROPS: [
                                    {
                                        NAME: 'EXTENT',
                                        CODE: 'LOGO_EXTENT',
                                        VALUE: item.EXTENT
                                    },
                                    {
                                        NAME: 'COMMENTS',
                                        CODE: 'LOGO_COMMENTS',
                                        VALUE: item.COMMENTS
                                    },
                                    {
                                        NAME: 'FILE',
                                        CODE: 'LOGO_FILE',
                                        VALUE: item.FILE
                                    }
                                ]
                            });
                        }
                    })
                }
            },
            mixins: [currencyMixin]
        },
        'laminating': {
            template: '#laminating',
            data: function () {
                return {
                    sectionId: 14,
                    itemId: 11,
                    items: [
                        {
                            //ITEM_ID: '',
                            QUANTITY: 0,
                            COLOR: '',
                            COMMENTS: ''
                        }
                    ]
                }
            },
            ready: function () {
                if (
                    this.$root.order
                    &&
                    this.$root.order.selectedStand.hasOwnProperty('SERVICES')
                    &&
                    this.$root.order.selectedStand.SERVICES
                    &&
                    this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
                ) {
                    this.items = this.$root.order.selectedStand.SERVICES[this.sectionId];
                }
            },
            computed: {
                section: function () {
                    return this.$parent.sections[this.sectionId]
                },
                item: function () {
                    return this.section.ITEMS[this.itemId];
                },
                price: function () {
                    return this.item.PRICE;
                },
                //availableEquipment: function () {
                //    return this.$root.selectedStand.EQUIPMENT;
                //},
                colors: function () {
                    var result = [],
                        self = this;
                    if (this.item.PROPERTY_34.length > 0) {
                        this.item.PROPERTY_34.forEach(function (val, key) {
                            if (self.$root.colors.hasOwnProperty(val)) {
                                result.push(self.$root.colors[val]);
                            }
                        });
                    }

                    return result;
                },
                allColors: function () {
                    return this.$root.colors
                }
            },
            methods: {
                incQty: function (item) {
                    item.QUANTITY++;
                },
                decQty: function (item) {
                    if (item.QUANTITY > 0) {
                        item.QUANTITY--;
                    }
                },
                addItem: function () {
                    this.items.push({
                        //ITEM_ID: '',
                        QUANTITY: 0,
                        COLOR: '',
                        COMMENTS: ''
                    })
                },
                selectColor: function (item, color) {
                    item.COLOR = color;
                    $.arcticmodal('close');
                },
                getTotalPrice: function () {
                    var self = this;
                    return this.items.reduce(function (sum, item) {
                        return sum + parseInt(self.price * item.QUANTITY);
                    }, 0)
                },
                addToCart: function () {
                    var self = this;
                    this.items.forEach(function (item, index) {
                        if (item.QUANTITY > 0) {
                            self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                                ID: self.item.ID,
                                NAME: self.item.NAME + ' (' +self.allColors[item.COLOR].UF_NUM + ' ' + item.COLOR + ')',
                                CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                                QUANTITY: parseFloat(item.QUANTITY).toFixed(2),
                                PRICE: parseFloat(self.price).toFixed(2),
                                PROPS: [
                                    {
                                        NAME: 'Цвет',
                                        CODE: 'COLOR',
                                        VALUE: item.COLOR
                                    },
                                    {
                                        NAME: 'Коммент',
                                        CODE: 'COMMENTS',
                                        VALUE: item.COMMENTS
                                    }//,
                                    //{
                                    //    NAME: 'ITEM_ID',
                                    //    CODE: 'ITEM_ID',
                                    //    VALUE: item.ITEM_ID
                                    //}
                                ]
                            });
                        }
                    })
                }
            },
            mixins: [currencyMixin]
        },
        'full-color-printing': {
            template: '#full-color-printing',
            data: function () {
                return {
                    sectionId: 18,
                    itemId: 26,
                    items: [
                        {
                            WIDTH: '',
                            HEIGHT: '',
                            QUANTITY: 0,
                            LINK: '',
                            COMMENTS: ''
                        }
                    ]
                }
            },
            ready: function () {
                if (
                    this.$root.order
                    &&
                    this.$root.order.selectedStand.hasOwnProperty('SERVICES')
                    &&
                    this.$root.order.selectedStand.SERVICES
                    &&
                    this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
                ) {
                    this.items = this.$root.order.selectedStand.SERVICES[this.sectionId];
                }
            },
            computed: {
                section: function () {
                    return this.$parent.sections[this.sectionId]
                },
                item: function () {
                    return this.section.ITEMS[this.itemId];
                },
                event: function () {
                    return this.$root.curEvent
                },
                price: function () {
                    return this.item.PRICE;
                }
            },
            methods: {
                incQty: function (item) {
                    item.QUANTITY++;
                },
                decQty: function (item) {
                    if (item.QUANTITY > 0) {
                        item.QUANTITY--;
                    }
                },
                addItem: function () {
                    this.items.push({
                        WIDTH: '',
                        HEIGHT: '',
                        QUANTITY: 0,
                        LINK: '',
                        COMMENTS: ''
                    })
                },
                getTotalPrice: function () {
                    var self = this;
                    return this.items.reduce(function (sum, item) {
                        return sum + parseInt(self.price * item.QUANTITY);
                    }, 0)
                },
                addToCart: function () {
                    var self = this;
                    this.items.forEach(function (item, index) {
                        if (parseInt(item.HEIGHT) > 0 && parseInt(item.WIDTH) > 0 && item.QUANTITY > 0) {
                            self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                                ID: self.item.ID,
                                NAME: self.item.NAME,
                                CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                                QUANTITY: item.QUANTITY,
                                PRICE: self.price * (item.WIDTH * item.HEIGHT) / 1000000,
                                PROPS: [
                                    {
                                        NAME: 'WIDTH',
                                        CODE: 'WIDTH',
                                        VALUE: item.WIDTH
                                    },
                                    {
                                        NAME: 'HEIGHT',
                                        CODE: 'HEIGHT',
                                        VALUE: item.HEIGHT
                                    },
                                    {
                                        NAME: 'LINK',
                                        CODE: 'LINK',
                                        VALUE: item.LINK
                                    },
                                    {
                                        NAME: 'COMMENTS',
                                        CODE: 'COMMENTS',
                                        VALUE: item.COMMENTS
                                    }
                                ]
                            });
                        }
                    })
                }
            },
            mixins: [currencyMixin]
        }
    },
    mixins: [toggleableMixin]
});


// Компонент: "Дополнительное оборудование".
Vue.component('additional-equipment', {
    template: '#additional-equipment',
    data: function () {
        return {
            selectedColor: ''
        }
    },
    ready: function () {
        if (
            this.$parent.selectedStand
            && this.$parent.selectedStand.hasOwnProperty('OPTIONS')
            && this.$parent.selectedStand.OPTIONS
            && this.$parent.selectedStand.OPTIONS.hasOwnProperty(this.section.ID)
            && this.$parent.selectedStand.OPTIONS[this.section.ID].hasOwnProperty(this.item.ID)
        ) {
            Vue.set(this.item, 'QUANTITY', this.$root.selectedStand.OPTIONS[this.section.ID][this.item.ID].QUANTITY);

            if (this.$parent.selectedStand.OPTIONS[this.section.ID][this.item.ID].PROPS.hasOwnProperty('COLOR')) {
                this.selectedColor = this.$parent.selectedStand.OPTIONS[this.section.ID][this.item.ID].PROPS.COLOR.VALUE
								  || this.$parent.selectedStand.OPTIONS[this.section.ID][this.item.ID].PROPS.COLOR;
            }
        } else {
            if (typeof this.item == 'object') {
				for (var i in this.$parent.selectedStand.EQUIPMENT) {
					var eq = this.$parent.selectedStand.EQUIPMENT[i];
					if (eq.ID == this.item.ID) {
						Vue.set(this.item, 'STANDART', eq.QUANTITY);
					}
				}
                Vue.set(this.item, 'QUANTITY', 0);
            }
        }
    },
    props: ['item', 'section'],
    methods: {
        addToCart: function () {
            if (this.colors && !this.selectedColor) {
                $('#modalColorError').arcticmodal();
            } else {
                if (this.item.QUANTITY > 0) {
                    this.$root.$set('selectedStand.OPTIONS[' + this.section.ID + '][' + this.item.ID + ']', {
                        ID: this.item.ID,
                        NAME: this.item.NAME,
                        PRICE: this.price,
                        QUANTITY: this.item.QUANTITY,
                        PROPS: {
                            COLOR: {
                                NAME: 'Цвет',
                                CODE: 'COLOR',
                                VALUE: this.selectedColor
                            }
                        }
                    })
                } else {
                    Vue.delete(this.$root.selectedStand.OPTIONS[this.section.ID], this.item.ID);
                }
            }
        },
        incQty: function () {
            this.item.QUANTITY++;
			this.addToCart();
        },
        decQty: function () {
            if (this.item.QUANTITY > 0) {
                this.item.QUANTITY--;
				this.addToCart();			
            }
        }
    },
    computed: {
        price: function () {
            return this.item.PRICE
        },
        colors: function () {
            var colors = !$.isEmptyObject(this.item.EQ_COLORS) ? this.item.EQ_COLORS : false;
			
            if (typeof colors == 'object') {
                if (Object.keys(colors).length == 1) {
                    return {ID: Object.keys(colors)[0], VALUE: colors[Object.keys(colors)[0]]}
                } else {
                    return colors;
                }
            } else {
                return colors;
            }

        },
        colorsLength: function () {
            return Object.keys(array_combine(this.item.PROPERTY_VALUE_ID_38, this.item.PROPERTY_38)).length;
        },
        ordered: function () {
            return this.$root.selectedStand.OPTIONS[this.section.ID][this.item.ID].QUANTITY
        },
        description: function () {
            return this.$root.curLang == 'EN' ? this.item.PROPERTY_61.TEXT : this.item.PROPERTY_60.TEXT;
        }
    },
    mixins: [currencyMixin]
});


// Компонент: "Подвесные конструкции".
Vue.component('hanging-structure', {
    template: '#hanging-structure',
    data: function () {
        return {
            visible: false,
            sectionId: 7
        }
    },
    computed: {
        components: function () {
            return this.$children;
        },
        section: function () {
            return this.$parent.services.SECTIONS[this.sectionId];
        },
        sections: function () {
            return this.section.SECTIONS;
        }
    },
    methods: {
        save: function () {
            this.components.forEach(function (component) {
                component.addToCart();
            });
        }
    },
    mixins: [toggleableMixin]
});


// Компонент: "".
Vue.component('suspension-points', {
    template: '#suspension-points',
    data: function () {
        return {
            sectionId: 20,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0
                }
            ]
        }
    },
    ready: function () {
        if (
            this.$root.selectedStand
            &&
            this.$root.selectedStand.hasOwnProperty('SERVICES')
            &&
            this.$root.selectedStand.SERVICES
            &&
            this.$root.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            this.selectedItems = this.$root.selectedStand.SERVICES[this.sectionId];
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});


// Компонент: "".
Vue.component('advertising-materials-file', {
    template: '#advertising-materials-file',
    data: function () {
        return {
            sectionId: 21,
            itemId: 31,
            selectedItems: [
                {
                    FILE_ID: '',
                    QUANTITY: 0,
                    COMMENTS: ''
                }
            ]
        }
    },
    ready: function () {
        if (
            this.$root.selectedStand
            &&
            this.$root.selectedStand.hasOwnProperty('SERVICES')
            &&
            this.$root.selectedStand.SERVICES
            &&
            this.$root.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            this.selectedItems = this.$root.selectedStand.SERVICES[this.sectionId];
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        item: function () {
            return this.items[this.itemId];
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                FILE_ID: '',
                QUANTITY: 0,
                COMMENTS: ''
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.FILE_ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: self.item.ID,
                        NAME: self.item.NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PROPS: [
                            {
                                NAME: 'Коммент',
                                CODE: 'COMMENTS',
                                VALUE: item.COMMENTS
                            },
                            {
                                NAME: 'Файл',
                                CODE: 'FILE_ID',
                                VALUE: item.FILE_ID
                            }
                        ]
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});


// Компонент: "".
Vue.component('hanging-structure-mock-up', {
    template: '#hanging-structure-mock-up',
    data: function () {
        return {
            sectionId: 28,
            itemId: 41,
            selectedItems: [
                {
                    FILE_ID: '',
                    QUANTITY: 0,
                    COMMENTS: ''
                }
            ]
        }
    },
    ready: function () {
        if (
            this.$root.order
            &&
            this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            &&
            this.$root.order.selectedStand.SERVICES
            &&
            this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            this.selectedItems = this.$root.order.selectedStand.SERVICES[this.sectionId];
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        item: function () {
            return this.items[this.itemId];
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                FILE_ID: '',
                QUANTITY: 0,
                COMMENTS: ''
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.FILE_ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: self.item.ID,
                        NAME: self.item.NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PROPS: [
                            {
                                NAME: 'Коммент',
                                CODE: 'COMMENTS',
                                VALUE: item.COMMENTS
                            },
                            {
                                NAME: 'Файл',
                                CODE: 'FILE_ID',
                                VALUE: item.FILE_ID
                            }
                        ]
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});


// Компонент: "".
Vue.component('hanging-structure-details', {
    template: '#hanging-structure-details',
    data: function () {
        return {
            sectionId: 29,
            itemId: 42,
            fields: {
                companyName: '',
                pavilionNum: '',
                hallNum: '',
                standNum: '',
                size: '',
                material: '',
                weight: '',
                listOfTheEquipmentPlacingOnTheStructure: '',
                material2: '',
                weightPerPoint: '',
                height: '',
                totalWeight: '',
                personInChargeOfTheProjectOfTheStructure: '',
                personInChargeOfMountingWorks: '',
                mobilePhone: ''
            }
        }
    },
    ready: function () {
        if (
            this.$root.order
            &&
            this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            &&
            this.$root.order.selectedStand.SERVICES
            &&
            this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            this.fields = this.$root.order.selectedStand.SERVICES[this.sectionId][0];
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        item: function () {
            return this.items[this.itemId];
        }
    },
    methods: {
        addToCart: function () {
            var self = this;
            self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][0]', {
                ID: self.item.ID,
                NAME: self.item.NAME,
                CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                QUANTITY: 1,
                PROPS: [
                    {
                        NAME: 'Компания',
                        CODE: 'companyName',
                        VALUE: self.fields.companyName
                    },
                    {
                        NAME: 'Номер павильона',
                        CODE: 'pavilionNum',
                        VALUE: self.fields.pavilionNum
                    },
                    {
                        NAME: 'Номер холла',
                        CODE: 'hallNum',
                        VALUE: self.fields.hallNum
                    },
                    {
                        NAME: 'Номер стенда',
                        CODE: 'standNum',
                        VALUE: self.fields.standNum
                    },
                    {
                        NAME: 'Размер',
                        CODE: 'size',
                        VALUE: self.fields.size
                    },
                    {
                        NAME: 'Материал',
                        CODE: 'material',
                        VALUE: self.fields.material
                    },
                    {
                        NAME: 'Вес',
                        CODE: 'weight',
                        VALUE: self.fields.weight
                    },
                    {
                        NAME: 'List of the equipment placing on the structure',
                        CODE: 'listOfTheEquipmentPlacingOnTheStructure',
                        VALUE: self.fields.listOfTheEquipmentPlacingOnTheStructure
                    },
                    {
                        NAME: 'Material 2',
                        CODE: 'material2',
                        VALUE: self.fields.material2
                    },
                    {
                        NAME: 'Weight per point',
                        CODE: 'weightPerPoint',
                        VALUE: self.fields.weightPerPoint
                    },
                    {
                        NAME: 'Height',
                        CODE: 'height',
                        VALUE: self.fields.height
                    },
                    {
                        NAME: 'Total weight',
                        CODE: 'totalWeight',
                        VALUE: self.fields.totalWeight
                    },
                    {
                        NAME: 'Person in charge of the project of the structure',
                        CODE: 'personInChargeOfTheProjectOfTheStructure',
                        VALUE: self.fields.personInChargeOfTheProjectOfTheStructure
                    },
                    {
                        NAME: 'Person in charge of mounting works',
                        CODE: 'personInChargeOfMountingWorks',
                        VALUE: self.fields.personInChargeOfMountingWorks
                    },
                    {
                        NAME: 'Mobile phone',
                        CODE: 'mobilePhone',
                        VALUE: self.fields.mobilePhone
                    }
                ]
            });
        }
    }
});



// Компонент: "Пропуска для машин".
Vue.component('car-passes', {
    template: '#car-passes',
    data: function () {
        return {
            visible: false,
            sectionId: 15
        }
    },
    computed: {
        components: function () {
            return this.$children;
        },
        section: function () {
            return this.$parent.services.SECTIONS[this.sectionId];
        },
        sections: function () {
            return this.section.SECTIONS;
        }
    },
    methods: {
        save: function () {
            this.components.forEach(function (component) {
                component.addToCart();
            });
        }
    },
    mixins: [toggleableMixin]
});



// Компонент: "VIP пропуска".
Vue.component('vip-passes', {
    template: '#vip-passes',
    data: function () {
        return {
            sectionId: 26,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0
                }
            ]
        }
    },
    ready: function () {
        if (
            this.$root.order
            &&
            this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            &&
            this.$root.order.selectedStand.SERVICES
            &&
            this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            this.selectedItems = this.$root.order.selectedStand.SERVICES[this.sectionId];
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin,
		currencyMixin
    ]
});



// Компонент: "Пропуска для зоны загрузки / разгрузки".
Vue.component('car-passes-to-loading-unloading-zone', {
    template: '#car-passes-to-loading-unloading-zone',
    data: function () {
        return {
            sectionId: 27,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0
                }
            ]
        }
    },
    ready: function () {
        if (
            this.$root.order
            &&
            this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            &&
            this.$root.order.selectedStand.SERVICES
            &&
            this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            this.selectedItems = this.$root.order.selectedStand.SERVICES[this.sectionId];
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin,
		currencyMixin
    ]
});



// Компонент: "Персонал".
Vue.component('temporary-staff', {
    template: '#temporary-staff',
    data: function () {
        return {
            sectionId: 8,
            visible: false
        }
    },
    computed: {
        section: function () {
            return this.$parent.services.SECTIONS[this.sectionId];
        },
        sections: function () {
            return this.section.SECTIONS;
        },
        components: function () {
            return this.$children;
        }
    },
    methods: {
        save: function () {
            this.components.forEach(function (component) {
                component.addToCart();
            });
        }
    },
    mixins: [toggleableMixin]
});



// Компонент: "Охрана".
Vue.component('stand-security', {
    template: '#stand-security',
    data: function () {
        return {
            sectionId: 22,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0,
                    calendar: {
                        dates: [],
                        datesType: 'multiple'
                    },
                    timeStart: '08:00',
                    timeEnd: '20:00'
                }
            ]
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        ordered: function () {
            return this.$root.order.selectedStand.SERVICES[22];
        }
    },
    ready: function () {
        if (
            this.$root.order
            && this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            && this.$root.order.selectedStand.SERVICES
            && this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            Vue.set(this, 'selectedItems', this.$root.order.selectedStand.SERVICES[this.sectionId]);
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0,
                calendar: {
                    dates: [],
                    datesType: 'multiple'
                },
                timeStart: '09:00',
                timeEnd: '23:00'
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    var hoursCount, daysCount;
                    if (item.calendar.datesType == 'range') {
                        try {
                            var date1 = item.calendar.dates[0].split('-');
                            var date2 = item.calendar.dates[1].split('-');
                            
                            date1 = date1[2] + '/' + date1[1] + '/' + date1[0];
                            date2 = date2[2] + '/' + date2[1] + '/' + date2[0];
                            
                            daysCount = Date.getDaysBetween(new Date(date1), new Date(date2)) + 1;
                        } catch (e) {
                            daysCount = 0;
                        }
                        if (daysCount < 1) {
                            daysCount = 1;
                        }
                    } else {
                        daysCount = item.calendar.dates.length;
                    }
					
					// Часы работы охраны.
					hoursCount = Date.getHoursBetween(new Date('01.01.1970 ' + item.timeStart), new Date('01.01.1970 ' + item.timeEnd));
					
					if (hoursCount < 1) {
						hoursCount = 1;
					}
					var multiplier = daysCount * hoursCount;
					
					
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE,
                        MULTIPLIER: multiplier,
                        PROPS: [
                            {
                                NAME: 'timeStart',
                                CODE: 'timeStart',
                                VALUE: item.timeStart
                            },
                            {
                                NAME: 'timeEnd',
                                CODE: 'timeEnd',
                                VALUE: item.timeEnd
                            },
                            {
                                NAME: 'calendar',
                                CODE: 'calendar',
                                VALUE: item.calendar
                            }
                        ]
                    });

                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});




// Компонент: "Переводчик".
Vue.component('interpreter', {
    template: '#interpreter',
    data: function () {
        return {
            sectionId: 23,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0,
                    calendar: {
                        dates: [],
                        datesType: 'multiple'
                    }
                }
            ]
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        }
    },
    ready: function () {
        if (
            this.$root.order
            && this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            && this.$root.order.selectedStand.SERVICES
            && this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            Vue.set(this, 'selectedItems', this.$root.order.selectedStand.SERVICES[this.sectionId]);
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0,
                calendar: {
                    dates: [],
                    datesType: 'multiple',
					dateFormat: 'yyyy/mm/dd'
                }
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    var daysCount;
					
                    if (item.calendar.datesType == 'multiple') {
                        daysCount = item.calendar.dates.length
                    } else {
						var date1 = item.calendar.dates[0].split('-');
						var date2 = item.calendar.dates[1].split('-');
						
						date1 = date1[2] + '/' + date1[1] + '/' + date1[0];
						date2 = date2[2] + '/' + date2[1] + '/' + date2[0];
						
                        try {
                            daysCount = Date.getDaysBetween(new Date(date1), new Date(date2), true);
                        } catch (e) {
                            daysCount = 1;
                        }
                    }

                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE,
                        MULTIPLIER: daysCount,
                        PROPS: [
                            {
                                NAME: 'calendar',
                                CODE: 'calendar',
                                VALUE: item.calendar
                            }
                        ]
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});



// Компонент: "Уборка".
Vue.component('stand-cleaning', {
    template: '#stand-cleaning',
    data: function () {
        return {
            sectionId: 24,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0
                }
            ]
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        ordered: function () {
            return this.$root.order.selectedStand.SERVICES[24];
        }
    },
    ready: function () {
        if (
            this.$root.order
            && this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            && this.$root.order.selectedStand.SERVICES
            && this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            Vue.set(this, 'selectedItems', this.$root.order.selectedStand.SERVICES[this.sectionId]);
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        if (
                            self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                            &&
                            self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                        ) {
                            Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                        }
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});




// Компонент: "Стендист".
Vue.component('stand-assistant', {
    template: '#stand-assistant',
    data: function () {
        return {
            sectionId: 25,
            selectedItems: [
                {
                    ID: 0,
                    QUANTITY: 0,
                    calendar: {
                        dates: [],
                        datesType: 'multiple'
                    }
                }
            ]
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        ordered: {
            cache: false,
            get: function () {
                return this.$root.order.selectedStand.SERVICES;
            }
        }
    },
    ready: function () {
        if (
            this.$root.order
            && this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            && this.$root.order.selectedStand.SERVICES
            && this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            Vue.set(this, 'selectedItems', this.$root.order.selectedStand.SERVICES[this.sectionId]);
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                ID: '',
                QUANTITY: 0,
                calendar: {
                    dates: [],
                    datesType: 'multiple'
                }
            });
        },
        addToCart: function () {
            var self = this;
            this.selectedItems.forEach(function (item, index) {
                if (item.ID && item.QUANTITY > 0) {
                    var daysCount;
					
                    if (item.calendar.datesType == 'multiple') {
                        daysCount = item.calendar.dates.length
                    } else {
                        var date1 = item.calendar.dates[0].split('-');
						var date2 = item.calendar.dates[1].split('-');
						
						date1 = date1[2] + '/' + date1[1] + '/' + date1[0];
						date2 = date2[2] + '/' + date2[1] + '/' + date2[0];
						
						try {
							daysCount = Date.getDaysBetween(new Date(date1), new Date(date2), true);
						} catch (e) {
							daysCount = 1;
						}
                    }

                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.items[item.ID].PRICE,
                        MULTIPLIER: daysCount,
                        PROPS: [
                            {
                                NAME: 'calendar',
                                CODE: 'calendar',
                                VALUE: item.calendar
                            }
                        ]
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    mixins: [
        quantityMixin, currencyMixin
    ]
});



// Компонент: "".
Vue.component('additional-buildup-time', {
    template: '#additional-buildup-time',
    data: function () {
        return {
            sectionId: 30,
            itemId: 77,
            selectedItems: [
                {
                    ID: '',
                    QUANTITY: 0,
                    BOOT_NUMBER: '',
                    AREA: '',
                    HOURS: '',
                    dates: {
                        dateStart: '',
                        dateEnd: ''
                    },
                    timeStart: '09:00',
                    timeEnd: '23:00'
                }
            ]
        }
    },
    computed: {
        section: function () {
            return this.$parent.sections[this.sectionId];
        },
        items: function () {
            return this.section.ITEMS;
        },
        item: function () {
            return this.items[this.itemId];
        }
    },
    methods: {
        addItem: function () {
            this.selectedItems.push({
                BOOT_NUMBER: '',
                AREA: '',
                HOURS: '',
                dates: {
                    dateStart: '',
                    dateEnd: ''
                },
                timeStart: '09:00',
                timeEnd: '23:00'
            })
        },
        addToCart: function () {
            this.selectedItems.forEach(function (item, index) {
                if (item.AREA && item.HOURS > 0) {
                    self.$root.$set('selectedStand.SERVICES[' + self.section.ID + '][' + index + ']', {
                        ID: item.ID,
                        NAME: self.items[item.ID].NAME,
                        CART_SECTION: {ID: self.$parent.section.ID, NAME: self.$parent.section.NAME},
                        QUANTITY: item.QUANTITY,
                        PRICE: self.item.PRICE * item.HOURS * item.AREA,
                        PROPS: [
                            {
                                NAME: 'dateStart',
                                CODE: 'dateStart',
                                VALUE: item.dates.dateStart
                            },
                            {
                                NAME: 'dateEnd',
                                CODE: 'dateEnd',
                                VALUE: item.dates.dateEnd
                            }
                        ]
                    });
                } else {
                    if (
                        self.$root.selectedStand.SERVICES.hasOwnProperty(self.section.ID)
                        &&
                        self.$root.selectedStand.SERVICES[self.section.ID].hasOwnProperty(index)
                    ) {
                        Vue.delete(self.$root.selectedStand.SERVICES[self.section.ID], index);
                    }
                }
            })
        }
    },
    ready: function () {
        if (
            this.$root.order
            && this.$root.order.selectedStand.hasOwnProperty('SERVICES')
            && this.$root.order.selectedStand.SERVICES
            && this.$root.order.selectedStand.SERVICES.hasOwnProperty(this.sectionId)
        ) {
            Vue.set(this, 'selectedItems', this.$root.order.selectedStand.SERVICES[this.sectionId]);
        }
    }
});



// Компонент: "Корзина".
Vue.component('basket', {
    template: '#basket',
    computed: {
		taxPrice: function () {
            return this.$parent.taxPrice || 0;
        },
        totalPrice: function () {
            return this.$parent.summaryPrice || 0;
        },
        selectedStand: function () {
            return this.$parent.selectedStand;
        },
        selectedParams: function () {
            return this.$parent.selectedParams;
        },
        allPrices: function () {
            return this.$parent.allPrices;
        },
        allOptions: function () {
            return this.$parent.allOptions;
        },
        allServices: function () {
            return this.$parent.allServices;
        }
    },
    methods: {
        nextStep: function () {
            this.$parent.nextStep();
        },
        prevStep: function () {
            this.$parent.prevStep();
        }
    },
    mixins: [currencyMixin]
});



// Фильтр формата валюты.
Vue.filter('exists_steps', function (val, separator) {
    return '';
});

// Фильтр формата валюты.
Vue.filter('format_number', function (val, separator) {
    if (val !== undefined && val) {
        return '€' + val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + separator);
    }
});

// Фильтр формата цены.
Vue.filter('format_currency', function (val, separator, pattern) {
    var result = '';
    
    if ((val ^ 0) !== val) {
        val = parseFloat(val).toFixed(2);
    }
    
    if (val !== undefined && val) {
        result = val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + separator);
        if (pattern) {
            result = pattern.replace(/#/, result);
        }
    }
    return result;
});

// Фильтр излишка.
Vue.filter('overIncluded', function (arr) {
    return arr.filter(function (val) {
        return val.QUANTITY > val.COUNT;
    });
});

// Фильтр видимости в корзине.
Vue.filter('visibleInCart', function (arr) {
    var res = [];
    if (!$.isEmptyObject(arr)) {
        $.each(arr, function (key, val) {
            if (val && (val.PRICE && parseInt(val.PRICE) > 0) || val.ID == 5) {
                res.push(val);
            }
        });
    }

    return res;
});

// Фильтр видимости шагов.
Vue.filter('visibleSteps', function (arr, standId) {
    var res = {};
    $.each(arr, function (key, val) {
        if ( !([2,4].indexOf(parseInt(val.NUM)) != -1 && standId == 0) ) {
            res[key] = val;
        }
    });
    
    return res;
});

// Фильтр перевода.
Vue.filter('t', function(val) {
    if(typeof langMessages != 'undefined' && langMessages[0].hasOwnProperty(val)) {
        return langMessages[0][val];
    }

    return val;
});


// Директива "Календарь".
Vue.directive('datepicker', {
    twoWay: true,

    bind: function () {
        var self = this;
        var dp = $(this.el).multiDatesPicker({
            dateFormat: 'mm-dd-yy',
            minDate: 0,
            maxPicks: 2,
            autoclose: true,
            onSelect: function (date) {
                var dates;
                dates = $(dp).multiDatesPicker('getDates');
                self.set({dateStart: dates[0], dateEnd: dates[1]});

            }
        });
        $(dp).children().hide();
        $(dp).on('click', function (e) {
            e.stopPropagation();
            $(this).children().toggle();
        })

    },
    update: function (value) {
        $(this.el).multiDatesPicker('value', value);
    }
});


// Директива "Календарь".
Vue.directive('pickmeup', {
    twoWay: true,
    bind: function () {
        var self = this,
            updateDt;

        (updateDt = function (el) {
            // Вызываем плагин
            Vue.nextTick(function () {
                $(el).find('.dpBlock').first().pickmeup({
                    flat: true,
                    mode: $(el).find('.dpBlock').first().attr('data-mode'),
                    change: function (formatted_date) {
                        self.set({
                            dates: formatted_date,
                            datesType: $(self.el).find('.dpBlock').first().attr('data-mode')
                        });
                    }
                });

                // Убиваем текущую дату из выбранных
                $(el).find('.dpBlock').first().pickmeup('clear');

                // Убиваем предыдущий результат
                self.set({
                    dates: [],
                    datesType: $(self.el).find('.dpBlock').first().attr('data-mode')
                });
            })

        })(self.el);

        // Переключаем режимы
        $(self.el).on('change', '.changeMode', function () {
            if ($(this).prop('checked')) {
                $(self.el).find('.dpBlock').first().attr('data-mode', 'range');
                self.set({
                    dates: [],
                    datesType: $(self.el).find('.dpBlock').first().attr('data-mode')
                });
            } else {
                $(self.el).find('.dpBlock').first().attr('data-mode', 'multiple');
                self.set({
                    dates: [],
                    datesType: $(self.el).find('.dpBlock').first().attr('data-mode')
                });
            }
            // Убиваем календарь
            $(self.el).find('.dpBlock').first().pickmeup('destroy');

            // Вызываем календарь с новыми параметрами
            updateDt(self.el);
        });


        $(self.el).on('click', function () {
            // Показываем окно
            $(this).find('.loolee').addClass('active');
        });
		
        // Очищаем результат в календаре и инпуте
        $(self.el).on('click', '.dateClear', function (e) {
            e.preventDefault();
            $(self.el).find('.dpBlock').first().pickmeup('clear');
            self.set({
                dates: [],
                datesType: $(self.el).find('.dpBlock').first().attr('data-mode')
            });
        });
		
        // Закрываем окно при клике на "ОК"
        $(document).on('click', '.looleeClose', function (e) {
            e.preventDefault();
            $(self.el).find('.loolee.active').removeClass('active');
        });
		
        $(document).mouseup(function (e) {
            var container = $('.loolee.active');
            if (container.has(e.target).length === 0) {
                container.removeClass('active');
            }
        });
    },
    update: function (value) {
        // console.log(value);
    }
});


// Директива "Стили".
Vue.directive('styler', {
    twoWay: true,
    priority: 1000,
    deep: true,
	
    bind: function () {
        var self = this;
        var el = $(this.el);
		
        el.styler().on('change', function () {
            value = this.value;
            if (el.is(':checkbox')) {
                value = !!el.is(':checked');
            }
            self.set(value);
        });
    },
    update: function (value) {
        this.el.value = value;
        var self = this;
        Vue.nextTick(function () {
            $(self.el).trigger('refresh');
        });
    }
});

Vue.directive('timepicker', {
    twoWay: true,
	
    bind: function () {
        var self = this;
        var el = $(this.el);
        ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'].forEach(function (val) {
            el.append('<option value="' + val + '">' + val + '</option>');
        });

        el.styler()
          .on('change', function () {
			self.set(this.value);
          });
    },
    update: function (value) {
        $(this.el).val(value).trigger('refresh');
    }
});

Vue.directive('fileupload', {
    twoWay: true,
    bind: function () {
        var self = this;
        var el = $(this.el);

        el.styler().fileupload({
            url: '/local/components/wolk/event.detail/ajax.php',
            type: 'POST',
            dataType: 'json',
            paramName: 'FILE',
            formData: {
                action: 'upload',
                sessid: BX.bitrix_sessid()
            },
            done: function (e, data) {
                self.set(data.result.files[0].id);
            },
            fail: function (e, data) {
                // console.log(data);
            }
        });
    }
});

//Vue.directive('colors', {
//    bind: function () {
//        var self = this;
//        var el = $(this.el);
//
//        el.find('li').tooltipster({
//            theme: 'colorsTip',
//            position: 'bottom',
//            arrow: false,
//            contentAsHTML: true,
//            trigger: 'hover'
//        });
//    }
//});

function array_combine(keys, values) {
    var new_array = {},
        keycount = keys && keys.length,
        i = 0;

    if (typeof values === 'object' && values.length == undefined) {
        values = Object.keys(values).map(function (key) {
            return values[key]
        });
    }

    // input sanitation
    if (typeof keys !== 'object' || typeof values !== 'object' || // Only accept arrays or array-like objects
        typeof keycount !== 'number' || typeof values.length !== 'number' || !keycount) { // Require arrays to have a count
        return false;
    }

    // number of elements does not match
    if (keycount != values.length) {
        // console.log('number of elements does not match');
        return false;
    }

    for (i = 0; i < keycount; i++) {
        new_array[keys[i]] = values[i];
    }

    return new_array;
}

Date.getHoursBetween = function (date1, date2) {
    var one_hour = 1000 * 60 * 60;

    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();

    var difference_ms = date2_ms - date1_ms;

    return Math.round(difference_ms / one_hour);
};

Date.getDaysBetween = function (date1, date2, including) {
    var one_day = 1000 * 60 * 60 * 24;
	
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();

    var difference_ms = date2_ms - date1_ms;

    if (including) {
        return Math.round(difference_ms / one_day) + 1;
    } else {
        return Math.round(difference_ms / one_day);
    }
};

Vue.transition('fade', {
    afterEnter: function (el) {
        setTimeout(function() {window.resizeEditor(itemsForSketch);}, 600);
    }
});