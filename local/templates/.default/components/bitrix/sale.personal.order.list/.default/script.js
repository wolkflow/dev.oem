$(function () {
    var vm = new Vue({
        el: 'body',
        data: {
            orderId: null,
            selectedStand: null,
            orderProps: null,
            totalPrice: null,
            curEvent: null,
            TOTAL_PRICE_FORMATTED: null
        },
        methods: {
            loadOrder: function(id) {
                var self = this;
                $.post('/local/components/wolk/event.detail/ajax.php', {
                    sessid: BX.bitrix_sessid(),
                    action: 'getOrder',
                    orderId: id
                }).done(function(data) {
                    self.selectedStand = data.selectedStand;
                    self.orderId = data.ID;
                    self.orderProps = data.PROPS;
                    self.totalPrice = data.totalPrice;
                    self.curEvent = data.curEvent;
                    self.TOTAL_PRICE_FORMATTED = data.TOTAL_PRICE_FORMATTED;
                    Vue.nextTick(function() {
                       self.showOrder();
                    });
                })
            },
            showOrder: function() {
                $("#order-detail").arcticmodal();
            }
        },
        computed: {
            selectedServices: {
                cache: false,
                get: function () {
                    var res = {};
                    if (!$.isEmptyObject(this.selectedStand.SERVICES)) {
                        $.each(this.selectedStand.SERVICES, function (groupId, services) {
                            $.each(services, function (serviceId, service) {
                                res[service.ID] = service;
                            })
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
            }
        }
    })
});

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

Vue.filter('format_number', function (val, separator) {
    if (val !== undefined && val) {
        val = parseFloat(val).toFixed(2);
        return val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + separator);
    }
});

Vue.filter('format_currency', function (val, separator, pattern) {
    var result = '';
    if (val !== undefined && val) {
        result = val.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + separator);
        if (pattern) {
            result = pattern.replace(/#/, result);
        }

        return result;
    }
});

Vue.filter('overIncluded', function (arr) {
    return arr.filter(function (val) {
        return val.QUANTITY > val.COUNT;
    });
});

Vue.filter('visibleInCart', function (arr) {
    var res = [];
    if (!$.isEmptyObject(arr)) {
        $.each(arr, function (key, val) {
            if (val && val.PRICE && parseInt(val.PRICE) > 0) {
                res.push(val)
            }
        });
    }

    return res;
});