var base_url = $("head").data("base-url");
var file_ext = $("head").data("file-ext");
var hash = $("head").data("hash");
var language = $("head").data("language");
require(["angular_app"], function(angular_app) {
    var processing = null;
    var filter_types = [];
    filter_types["price"] = "total_cost";
    filter_types["departure"] = "departure_tm";
    filter_types["arrival"] = "arrival_tm";
    filter_types["duration"] = "duration";
    angular_app.controller("flight_controller", ["$scope", "$http", "$timeout", "$filter", function($scope, $http, $timeout, $filter) {
        var fac = $scope;
        fac.min_price = fac.cmin_price = 0;
        fac.max_price = fac.cmax_price = 0;
        fac.total_count = fac.all_total_count = -2;
        fac.dmin_time = fac.rmin_time = 0;
        fac.dmax_time = fac.rmax_time = 24;
        fac.flights = [];
        fac.pageno = 1;
        fac.page_limit = 35;
        fac.currency = "ریال";
        fac.currency_val = 1;
        fac.stops = [];
        fac.airlines = [];
        fac.price_matrix = [];
        fac.airports = [];
        fac.order_by = "price";
        fac.load = true;
        fac.filter_modified = true;
        fac.sort_by = "asc";
        fac.sort_me = function(sort) {
            if (fac.order_by === sort)
                fac.sort_by = fac.sort_by === "asc" ? "desc" : "asc";
            else
                fac.sort_by = "asc";
            fac.order_by = sort;
            fac.filter_data();
        }
        fac.get_data = function(pageno) {
            fac.flights = [];
            fac.pageno = pageno;
            if (fac.load === true)
                fac.total_count = -2;
            else
                fac.total_count = -1;
            if (fac.load === true)
                filter_form = "";
            var filter_form = $("form#filter_form").serialize();
            var oby = filter_types[fac.order_by];
            $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                if (response.expired === "true") {
                    alert(lang_lib_session_expired);
                    window.location.href = base_url + "flight/session_expired/" + hash
                }
                if (fac.load === true) {
                    fac.load = false;
                    fac.total_count = fac.all_total_count = response.total_count;
                    fac.min_price = fac.cmin_price = response.min_price;
                    fac.max_price = fac.cmax_price = response.max_price;
                    fac.stops = response.stops;
                    fac.airlines = response.airlines;
                    fac.currency = response.currency;
                    fac.currency_val = response.currency_val;
                    fac.price_matrix = response.price_matrix;
                    fac.airports = response.airports;
                    if ($("div.price-slider").length > 0)
                        $("div.price-slider").slider({
                            range: true,
                            min: parseInt(fac.min_price),
                            max: parseInt(fac.max_price),
                            values: [fac.cmin_price, fac.cmax_price],
                            slide: function(event, ui) {
                                fac.cmin_price = ui.values[0];
                                fac.cmax_price = ui.values[1];
                                fac.$apply();
                                fac.filter_data();
                            }
                        });
                    if ($("div.departure-slider").length > 0)
                        $("div.departure-slider").slider({
                            range: true,
                            min: 0,
                            max: 24,
                            values: [fac.dmin_time, fac.dmax_time],
                            slide: function(event, ui) {
                                fac.dmin_time = ui.values[0];
                                fac.dmax_time = ui.values[1];
                                fac.$apply();
                                fac.filter_data();
                            }
                        });
                    if ($("div.return-slider").length > 0)
                        $("div.return-slider").slider({
                            range: true,
                            min: 0,
                            max: 24,
                            values: [fac.rmin_time, fac.rmax_time],
                            slide: function(event, ui) {
                                fac.rmin_time = ui.values[0];
                                fac.rmax_time = ui.values[1];
                                fac.$apply();
                                fac.filter_data();
                            }
                        });
                }
                angular.forEach(response.flights, function(value, key) {
                    value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                    value.departures = angular.fromJson(value.departures);
                    value.arrivals = angular.fromJson(value.arrivals);
                    value.prices = angular.fromJson(value.prices);
                    value.origin_destination = angular.fromJson(value.origin_destination);
                    this.push(value);
                }, fac.flights);
                fac.total_count = response.total_count;
            });
        };
        fac.filter_data = function() {
            fac.total_count = -1;
            if (processing === null && fac.all_total_count > 0)
                processing = $timeout(function() {
                    fac.flights = [];
                    fac.total_count = -1;
                    fac.pageno = 1;
                    var filter_form = $("form#filter_form").serialize();
                    var oby = filter_types[fac.order_by];
                    $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                        if (response.expired === "true") {
                            alert(lang_lib_session_expired);
                            window.location.href = base_url + "flight/session_expired/" + hash
                        }
                        angular.forEach(response.flights, function(value, key) {
                            value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                            value.departures = angular.fromJson(value.departures);
                            value.arrivals = angular.fromJson(value.arrivals);
                            value.prices = angular.fromJson(value.prices);
                            value.origin_destination = angular.fromJson(value.origin_destination);
                            this.push(value);
                        }, fac.flights);
                        fac.total_count = response.total_count;
                        processing = null;
                    });
                }, 2000, true).then(function() {});
        };
        fac.filter_airline_price_stop = function(airline, price, stops) {
            fac.flights = [];
            fac.pageno = 1;
            fac.total_count = -1;
            fac.filter_modified = false;
            fac.cmin_price = fac.cmax_price = price;
            fac.dmin_time = 0;
            fac.dmax_time = 24;
            $("form#filter_form :input[name='airlines[]'][value!='" + airline + "']").prop("checked", false);
            $("form#filter_form :input[name='airlines[]'][value='" + airline + "']").prop("checked", "checked");
            $("form#filter_form :input[name='stops[]'][value!='" + stops + "']").prop("checked", false);
            $("form#filter_form :input[name='stops[]'][value='" + stops + "']").prop("checked", "checked");
            $(document).find("form#filter_form #airline_NaN").prop("checked", false).removeAttr("checked");
            var filter_form = "stops[]=" + stops + "&price_filter=" + price + "-" + price + "&airlines[]=" + airline + "&departure_filter=0-24";
            var oby = filter_types[fac.order_by];
            $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                if (response.expired === "true") {
                    alert(lang_lib_session_expired);
                    window.location.href = base_url + "flight/session_expired/" + hash
                }
                angular.forEach(response.flights, function(value, key) {
                    value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                    value.departures = angular.fromJson(value.departures);
                    value.arrivals = angular.fromJson(value.arrivals);
                    value.prices = angular.fromJson(value.prices);
                    value.origin_destination = angular.fromJson(value.origin_destination);
                    this.push(value);
                }, fac.flights);
                fac.total_count = response.total_count;
            });
        };
        fac.filter_airline = function(airline) {
            fac.flights = [];
            fac.pageno = 1;
            fac.total_count = -1;
            fac.filter_modified = false;
            fac.cmin_price = fac.min_price;
            fac.cmax_price = fac.max_price;
            fac.dmin_time = 0;
            fac.dmax_time = 24;
            $("form#filter_form :input[name='airlines[]'][value!='" + airline + "']").prop("checked", false);
            $("form#filter_form :input[name='airlines[]'][value='" + airline + "']").prop("checked", "checked");
            $("form#filter_form :input[name='stops[]']").prop("checked", "checked");
            $(document).find("form#filter_form #airline_NaN").prop("checked", false).removeAttr("checked");
            var filter_form = "airlines[]=" + airline;
            var oby = filter_types[fac.order_by];
            $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                if (response.expired === "true") {
                    alert(lang_lib_session_expired);
                    window.location.href = base_url + "flight/session_expired/" + hash
                }
                angular.forEach(response.flights, function(value, key) {
                    value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                    value.departures = angular.fromJson(value.departures);
                    value.arrivals = angular.fromJson(value.arrivals);
                    value.prices = angular.fromJson(value.prices);
                    value.origin_destination = angular.fromJson(value.origin_destination);
                    this.push(value);
                }, fac.flights);
                fac.total_count = response.total_count;
            });
        };
        fac.stop_exists = function(pricelist, stop) {
            var result = 0;
            if (pricelist !== undefined)
                angular.forEach(pricelist, function(value) {
                    stop_price = value.split(":");
                    if (parseInt(stop) == parseInt(stop_price[0]))
                        result = stop_price[1];
                });
            return result;
        }
        fac.fare_details = function(idx) {
            var faremodal = $(document).find("#faremodal");
            faremodal.find(".person_fare_details").html("");
            faremodal.find(".cancellation_details").html("");
            faremodal.find(".airrules_more_details").html("<div class='loading'></div>");
            $("[data-target='#faremodal']").click();
            $http.get(base_url + "flight/air_rules/" + hash + "/" + idx).success(function(response) {
                if (response !== "false")
                    if (response.status === "true")
                        faremodal.find(".airrules_more_details").html(response.fare_rules);
                    else if (response.expired === "true") {
                        alert(lang_lib_session_expired);
                        window.location.href = base_url + "flight/session_expired/" + hash
                    } else {
                        if (response.result !== undefined)
                            faremodal.find(".airrules_more_details").html(response.result);
                        else
                            faremodal.find(".airrules_more_details").html(response.fare_rules);
                        $timeout(function() {
                            window.location.href = base_url + "flight/lists/" + hash;
                        }, 2000, true).then(function() {});
                    }
            });
        }
        fac.baggage_details = function(idx) {
            var baggagemodal = $(document).find("#baggagemodal");
            baggagemodal.find(".baggage_details").html("<div class='loading'></div>");
            $("[data-target='#baggagemodal']").click();
            $http.get(base_url + "flight/baggage_rules/" + hash + "/" + idx).success(function(response) {
                if (response !== "false")
                    if (response.status === "true")
                        baggagemodal.find(".baggage_details").html(response.baggage_rules);
                    else if (response.expired === "true") {
                        alert(lang_lib_session_expired);
                        window.location.href = base_url + "flight/session_expired/" + hash
                    } else {
                        if (response.result !== undefined)
                            baggagemodal.find(".baggage_details").html(response.result);
                        else
                            baggagemodal.find(".baggage_details").html(response.baggage_rules);
                        $timeout(function() {
                            window.location.href = base_url + "flight/lists/" + hash;
                        }, 2000, true).then(function() {});
                    }
            });
        }
        fac.get_price_per_adult = function(data) {
            price_per_adult = 0;
            angular.forEach(data, function(value, key) {
                if (value.person_type === 'Adult') {
                    price_per_adult = value.total_cost;
                }
            });
            return price_per_adult;
        };
        fac.flight_validate = function(idx) {
            $(document).find("#revalidatemodal").css({
                "display": "block"
            });
            $http.get(base_url + "flight/revalidate/" + hash + "/" + idx).success(function(response) {
                if (response.redirect === "true")
                    window.location.href = base_url + "flight/lists/" + hash;
                else
                    window.location.href = base_url + "flight/prebook/" + hash + "/" + idx;
            });
        };
        fac.get_data(1);
    }]);
    angular_app.controller("flight_prebook", ["$scope", "$http", "$timeout", "$filter", function($scope, $http, $timeout, $filter) {
        var fac = $scope;
        fac.flights = [];
        var temp_flights = [];
        fac.currency = $(".flight_prebook_block .flightlist").data("href");
        fac.currency_val = $(".flight_prebook_block .flightlist .flight_result").data("href");
        fac.airlines = [];
        fac.airports = [];
        var flight_777 = angular.fromJson($(".flight_prebook_block").data("href"));
        temp_flights.push(flight_777);
        angular.forEach(temp_flights, function(value, key) {
            value.departures = angular.fromJson(value.departures);
            value.arrivals = angular.fromJson(value.arrivals);
            value.prices = angular.fromJson(value.prices);
            value.origin_destination = angular.fromJson(value.origin_destination);
            this.push(value);
        }, fac.flights);
        fac.fare_details = function(idx) {
            var faremodal = $(document).find("#faremodal");
            faremodal.find(".person_fare_details").html("");
            faremodal.find(".cancellation_details").html("");
            faremodal.find(".airrules_more_details").html("<div class='loading'></div>");
            $("[data-target='#faremodal']").click();
            $http.get(base_url + "flight/air_rules/" + hash + "/" + idx).success(function(response) {
                if (response !== "false")
                    if (response.status === "true") {
                        faremodal.find(".airrules_more_details").html(response.fare_rules);
                        faremodal.find(".cancellation_details").html(response.cancellation_rules);
                    } else if (response.expired === "true") {
                        alert(lang_lib_session_expired);
                        window.location.href = base_url + "flight/session_expired/" + hash
                    } else {
                        if (response.result !== undefined)
                            faremodal.find(".airrules_more_details").html(response.result);
                        else
                            faremodal.find(".airrules_more_details").html(response.fare_rules);
                        $timeout(function() {
                            window.location.href = base_url + "flight/lists/" + hash;
                        }, 2000, true).then(function() {});
                    }
            });
        }
        fac.baggage_details = function(idx) {
            var baggagemodal = $(document).find("#baggagemodal");
            baggagemodal.find(".baggage_details").html("<div class='loading'></div>");
            $("[data-target='#baggagemodal']").click();
            $http.get(base_url + "flight/baggage_rules/" + hash + "/" + idx).success(function(response) {
                if (response !== "false")
                    if (response.status === "true")
                        baggagemodal.find(".baggage_details").html(response.baggage_rules);
                    else if (response.expired === "true") {
                        alert(lang_lib_session_expired);
                        window.location.href = base_url + "flight/session_expired/" + hash
                    } else {
                        if (response.result !== undefined)
                            baggagemodal.find(".baggage_details").html(response.result);
                        else
                            baggagemodal.find(".baggage_details").html(response.baggage_rules);
                        $timeout(function() {
                            window.location.href = base_url + "flight/lists/" + hash;
                        }, 2000, true).then(function() {});
                    }
            });
        }
    }]);
    angular_app.controller("domestic_controller", ["$scope", "$http", "$timeout", "$filter", function($scope, $http, $timeout, $filter) {
        var fac = $scope;
        fac.min_price = fac.cmin_price = 0;
        fac.max_price = fac.cmax_price = 0;
        fac.total_count = fac.all_total_count = -2;
        fac.dmin_time = fac.rmin_time = 0;
        fac.dmax_time = fac.rmax_time = 24;
        fac.flights = [];
        fac.rflights = [];
        fac.pageno = 1;
        fac.page_limit = 35;
        fac.currency = "ریال";
        fac.currency_val = 1;
        fac.is_twoway = "false";
        fac.stops = [];
        fac.airlines = [];
        fac.price_matrix = [];
        fac.airports = [];
        fac.selected_flight_price = 0;
        fac.departure_price = 0;
        fac.arrival_price = 0;
        fac.oflight = null;
        fac.rflight = null;
        fac.order_by = "price";
        fac.load = true;
        fac.filter_modified = true;
        fac.sort_by = "asc";
        fac.discount = 0.00;
        fac.sort_me = function(sort) {
            if (fac.order_by === sort)
                fac.sort_by = fac.sort_by === "asc" ? "desc" : "asc";
            else
                fac.sort_by = "asc";
            fac.order_by = sort;
            fac.filter_data();
        }
        fac.get_data = function(pageno) {
            fac.flights = [];
            fac.rflights = [];
            fac.pageno = pageno;
            fac.arrival_price = 0;
            fac.departure_price = 0;
            fac.selected_flight_price = 0;
            fac.oflight = null;
            fac.rflight = null;
            if (fac.load === true)
                fac.total_count = -2;
            else
                fac.total_count = -1;
            if (fac.load === true)
                filter_form = "";
            var filter_form = $("form#filter_form").serialize();
            var oby = filter_types[fac.order_by];
            $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                //jQuery("#mehdi").val(JSON.stringify(response));
                if (response.expired === "true") {
                    alert(lang_lib_session_expired);
                    window.location.href = base_url + "flight/session_expired/" + hash
                }
                if (fac.load === true) {
                    fac.load = false;
                    fac.total_count = fac.all_total_count = response.total_count;
                    fac.min_price = fac.cmin_price = response.min_price;
                    fac.max_price = fac.cmax_price = response.max_price;
                    fac.stops = response.stops;
                    fac.is_twoway = response.is_twoway;
                    fac.airlines = response.airlines;
                    fac.currency = "ریال";
                    fac.currency_val = response.currency_val;
                    fac.price_matrix = response.price_matrix;
                    fac.airports = response.airports;
                    $http.get(base_url + "flight/get_discount/").success(function(response) {
                        fac.discount = response;
                    });
                    if ($("div.price-slider").length > 0)
                        $("div.price-slider").slider({
                            range: true,
                            min: parseInt(fac.min_price),
                            max: parseInt(fac.max_price),
                            values: [fac.cmin_price, fac.cmax_price],
                            slide: function(event, ui) {
                                fac.cmin_price = ui.values[0];
                                fac.cmax_price = ui.values[1];
                                fac.$apply();
                                fac.filter_data();
                            }
                        });
                    if ($("div.departure-slider").length > 0)
                        $("div.departure-slider").slider({
                            range: true,
                            min: 0,
                            max: 24,
                            values: [fac.dmin_time, fac.dmax_time],
                            slide: function(event, ui) {
                                fac.dmin_time = ui.values[0];
                                fac.dmax_time = ui.values[1];
                                fac.$apply();
                                fac.filter_data();
                            }
                        });
                    if ($("div.return-slider").length > 0)
                        $("div.return-slider").slider({
                            range: true,
                            min: 0,
                            max: 24,
                            values: [fac.rmin_time, fac.rmax_time],
                            slide: function(event, ui) {
                                fac.rmin_time = ui.values[0];
                                fac.rmax_time = ui.values[1];
                                fac.$apply();
                                fac.filter_data();
                            }
                        });
                }
                angular.forEach(response.flights, function(value, key) {
                    if (key === 0) {
                        fac.departure_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                        fac.oflight = value.id;
                    }
                    value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                    value.departures = angular.fromJson(value.departures);
                    value.arrivals = angular.fromJson(value.arrivals);
                    value.prices = angular.fromJson(value.prices);
                    value.origin_destination = angular.fromJson(value.origin_destination);
                    this.push(value);
                }, fac.flights);
                if (fac.is_twoway === "true") {
                    angular.forEach(response.rflights, function(value, key) {
                        if (key === 0 && fac.departure_price > 0) {
                            fac.arrival_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                            fac.rflight = value.id;
                        }
                        value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                        value.departures = angular.fromJson(value.departures);
                        value.arrivals = angular.fromJson(value.arrivals);
                        value.prices = angular.fromJson(value.prices);
                        value.origin_destination = angular.fromJson(value.origin_destination);
                        this.push(value);
                    }, fac.rflights);
                }
                if (fac.arrival_price > 0)
                    fac.selected_flight_price = fac.arrival_price + fac.departure_price;
                fac.total_count = response.total_count;
            });
        };
        fac.filter_data = function() {
            fac.total_count = -1;
            if (processing === null && fac.all_total_count > 0)
                processing = $timeout(function() {
                    fac.flights = [];
                    fac.rflights = [];
                    fac.total_count = -1;
                    fac.pageno = 1;
                    fac.arrival_price = 0;
                    fac.departure_price = 0;
                    fac.selected_flight_price = 0;
                    fac.oflight = null;
                    fac.rflight = null;
                    var filter_form = $("form#filter_form").serialize();
                    var oby = filter_types[fac.order_by];
                    $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                        if (response.expired === "true") {
                            alert(lang_lib_session_expired);
                            window.location.href = base_url + "flight/session_expired/" + hash
                        }
                        angular.forEach(response.flights, function(value, key) {
                            if (key === 0) {
                                fac.departure_price = value.total_cost * 1;
                                fac.oflight = value.id;
                            }
                            value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                            value.departures = angular.fromJson(value.departures);
                            value.arrivals = angular.fromJson(value.arrivals);
                            value.prices = angular.fromJson(value.prices);
                            value.origin_destination = angular.fromJson(value.origin_destination);
                            this.push(value);
                        }, fac.flights);
                        if (fac.is_twoway === "true") {
                            angular.forEach(response.rflights, function(value, key) {
                                if (key === 0 && fac.departure_price > 0) {
                                    fac.arrival_price = value.total_cost * 1;
                                    fac.rflight = value.id;
                                }
                                value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                                value.departures = angular.fromJson(value.departures);
                                value.arrivals = angular.fromJson(value.arrivals);
                                value.prices = angular.fromJson(value.prices);
                                value.origin_destination = angular.fromJson(value.origin_destination);
                                this.push(value);
                            }, fac.rflights);
                        }
                        if (fac.arrival_price > 0)
                            fac.selected_flight_price = fac.arrival_price + fac.departure_price;
                        fac.total_count = response.total_count;
                        processing = null;
                    });
                }, 2000, true).then(function() {});
        };
        fac.get_price_per_adult = function(data) {
            price_per_adult = 0;
            angular.forEach(data, function(value, key) {
                if (value.person_type === 'Adult') {
                    price_per_adult = value.total_cost;
                }
            });
            return price_per_adult;
        };
        fac.filter_airline_price_stop = function(airline, price, stops) {
            fac.flights = [];
            fac.rflights = [];
            fac.pageno = 1;
            fac.total_count = -1;
            fac.filter_modified = false;
            fac.cmin_price = fac.cmax_price = price;
            fac.dmin_time = 0;
            fac.dmax_time = 24;
            fac.arrival_price = 0;
            fac.departure_price = 0;
            fac.selected_flight_price = 0;
            fac.oflight = null;
            fac.rflight = null;
            $("form#filter_form :input[name='airlines[]'][value!='" + airline + "']").prop("checked", false);
            $("form#filter_form :input[name='airlines[]'][value='" + airline + "']").prop("checked", "checked");
            $("form#filter_form :input[name='stops[]'][value!='" + stops + "']").prop("checked", false);
            $("form#filter_form :input[name='stops[]'][value='" + stops + "']").prop("checked", "checked");
            $(document).find("form#filter_form #airline_NaN").prop("checked", false).removeAttr("checked");
            var filter_form = "stops[]=" + stops + "&price_filter=" + price + "-" + price + "&airlines[]=" + airline + "&departure_filter=0-24";
            var oby = filter_types[fac.order_by];
            $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                if (response.expired === "true") {
                    alert(lang_lib_session_expired);
                    window.location.href = base_url + "flight/session_expired/" + hash
                }
                angular.forEach(response.flights, function(value, key) {
                    if (key === 0) {
                        fac.departure_price = value.total_cost * 1;
                        fac.oflight = value.id;
                    }
                    value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                    value.departures = angular.fromJson(value.departures);
                    value.arrivals = angular.fromJson(value.arrivals);
                    value.prices = angular.fromJson(value.prices);
                    value.origin_destination = angular.fromJson(value.origin_destination);
                    this.push(value);
                }, fac.flights);
                if (fac.is_twoway === "true") {
                    angular.forEach(response.rflights, function(value, key) {
                        if (key === 0 && fac.departure_price > 0) {
                            fac.arrival_price = value.total_cost * 1;
                            fac.rflight = value.id;
                        }
                        value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                        value.departures = angular.fromJson(value.departures);
                        value.arrivals = angular.fromJson(value.arrivals);
                        value.prices = angular.fromJson(value.prices);
                        value.origin_destination = angular.fromJson(value.origin_destination);
                        this.push(value);
                    }, fac.rflights);
                }
                if (fac.arrival_price > 0)
                    fac.selected_flight_price = fac.arrival_price + fac.departure_price;
                fac.total_count = response.total_count;
            });
        };
        fac.set_rflight = function(id, price) {
            fac.rflight = id;
            fac.arrival_price = price * 1;
            fac.selected_flight_price = fac.departure_price + fac.arrival_price;
        }
        fac.set_oflight = function(id, price) {
            fac.oflight = id;
            fac.departure_price = price * 1;
            fac.selected_flight_price = fac.departure_price + fac.arrival_price;
        }
        fac.filter_airline = function(airline) {
            fac.flights = [];
            fac.rflights = [];
            fac.pageno = 1;
            fac.total_count = -1;
            fac.filter_modified = false;
            fac.cmin_price = fac.min_price;
            fac.cmax_price = fac.max_price;
            fac.dmin_time = 0;
            fac.dmax_time = 24;
            fac.arrival_price = 0;
            fac.departure_price = 0;
            fac.selected_flight_price = 0;
            fac.oflight = null;
            fac.rflight = null;
            $("form#filter_form :input[name='airlines[]'][value!='" + airline + "']").prop("checked", false);
            $("form#filter_form :input[name='airlines[]'][value='" + airline + "']").prop("checked", "checked");
            $("form#filter_form :input[name='stops[]']").prop("checked", "checked");
            $(document).find("form#filter_form #airline_NaN").prop("checked", false).removeAttr("checked");
            var filter_form = "airlines[]=" + airline;
            var oby = filter_types[fac.order_by];
            $http.get(base_url + "flight/search/" + hash + "?page=" + fac.pageno + "&limit=" + fac.page_limit + "&fresh=" + fac.load + "&sort_by=" + fac.sort_by + "&order_by=" + oby + "&" + filter_form).success(function(response) {
                if (response.expired === "true") {
                    alert(lang_lib_session_expired);
                    window.location.href = base_url + "flight/session_expired/" + hash
                }
                var flight_temp_cost = 0;
                angular.forEach(response.flights, function(value, key) {
                    if (key === 0) {
                        fac.departure_price = value.total_cost * 1;
                        fac.oflight = value.id;
                    }
                    value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                    value.departures = angular.fromJson(value.departures);
                    value.arrivals = angular.fromJson(value.arrivals);
                    value.prices = angular.fromJson(value.prices);
                    value.origin_destination = angular.fromJson(value.origin_destination);
                    this.push(value);
                }, fac.flights);
                if (fac.is_twoway === "true") {
                    angular.forEach(response.rflights, function(value, key) {
                        if (key === 0 && fac.departure_price > 0) {
                            fac.arrival_price = value.total_cost * 1;
                            fac.rflight = value.id;
                        }
                        value.display_price = fac.get_price_per_adult(angular.fromJson(value.prices));
                        value.departures = angular.fromJson(value.departures);
                        value.arrivals = angular.fromJson(value.arrivals);
                        value.prices = angular.fromJson(value.prices);
                        value.origin_destination = angular.fromJson(value.origin_destination);
                        this.push(value);
                    }, fac.rflights);
                }
                if (fac.arrival_price > 0)
                    fac.selected_flight_price = fac.arrival_price + fac.departure_price;
                fac.total_count = response.total_count;
            });
        };
        fac.stop_exists = function(pricelist, stop) {
            var result = 0;
            if (pricelist !== undefined)
                angular.forEach(pricelist, function(value) {
                    stop_price = value.split(":");
                    if (parseInt(stop) == parseInt(stop_price[0]))
                        result = stop_price[1];
                });
            return result;
        }
        fac.fare_details = function(idx) {
            var faremodal = $(document).find("#faremodal");
            faremodal.find(".person_fare_details").html("");
            faremodal.find(".cancellation_details").html("");
            faremodal.find(".airrules_more_details").html("<div class='loading'></div>");
            $("[data-target='#faremodal']").click();
            $http.get(base_url + "flight/air_rules/" + hash + "/" + idx).success(function(response) {
                if (response !== "false")
                    if (response.status === "true")
                        faremodal.find(".airrules_more_details").html(response.fare_rules);
                    else if (response.expired === "true") {
                        alert(lang_lib_session_expired);
                        window.location.href = base_url + "flight/session_expired/" + hash
                    } else {
                        if (response.result !== undefined)
                            faremodal.find(".airrules_more_details").html(response.result);
                        else
                            faremodal.find(".airrules_more_details").html(response.fare_rules);
                        $timeout(function() {
                            window.location.href = base_url + "flight/lists/" + hash;
                        }, 2000, true).then(function() {});
                    }
            });
        }
        fac.baggage_details = function(idx) {
            var baggagemodal = $(document).find("#baggagemodal");
            baggagemodal.find(".baggage_details").html("<div class='loading'></div>");
            $("[data-target='#baggagemodal']").click();
            $http.get(base_url + "flight/baggage_rules/" + hash + "/" + idx).success(function(response) {
                if (response !== "false")
                    if (response.status === "true")
                        baggagemodal.find(".baggage_details").html(response.baggage_rules);
                    else if (response.expired === "true") {
                        alert(lang_lib_session_expired);
                        window.location.href = base_url + "flight/session_expired/" + hash
                    } else {
                        if (response.result !== undefined)
                            baggagemodal.find(".baggage_details").html(response.result);
                        else
                            baggagemodal.find(".baggage_details").html(response.baggage_rules);
                        $timeout(function() {
                            window.location.href = base_url + "flight/lists/" + hash;
                        }, 2000, true).then(function() {});
                    }
            });
        }
        fac.flight_validate = function(idx) {
            $(document).find("#revalidatemodal").css({
                "display": "block"
            });
            $http.get(base_url + "flight/revalidate/" + hash + "/" + idx).success(function(response) {
                if (response.redirect === "true")
                    window.location.href = base_url + "flight/lists/" + hash;
                else
                    window.location.href = base_url + "flight/prebook/" + hash + "/" + idx;
            });
        };
        fac.dom_flight_validate = function() {
            $(document).find("#revalidatemodal").css({
                "display": "block"
            });
            $http.get(base_url + "flight/dom_revalidate/" + hash + "/" + fac.oflight + "/" + fac.rflight + "/" + fac.discount).success(function(response) {
                if (response.redirect === "true")
                    window.location.href = base_url + "flight/lists/" + hash;
                else
                    window.location.href = base_url + "flight/prebook/" + hash + "/" + response.new_idx;
            });
        };
        fac.get_data(1);
        fac.put_comma_among_digit = function (digit) {
            return digit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        fac.mehdi = function (mehdi) {
            return JSON.stringify(mehdi);
        }
    }]);
    var ele = $(document).find(".flight_results_block").length > 0 ? $(document).find(".flight_results_block") : ($(document).find(".domestic_results_block").length > 0 ? $(document).find(".domestic_results_block") : $(document).find(".flight_prebook_block"));
    var is_init = ele.injector();
    if (is_init === undefined)
        angular.bootstrap(ele, ["app_custom"]);
});
$(document).ready(function() {
    //display_farsi(language);
    var touch = false;
    var fs = "form#flight_search";
    var dfs = "form#domestic_flight_search";
    if ($(fs).length > 0) {
        $(fs).validate({
            ignore: ".ignore"
        });
        if ($(fs + " .from_flight").length > 0)
            $(fs + " .from_flight").autocomplete({
                source: base_url + "flight/autocomplete",
                minLength: 2,
                response: function(event, ui) {
                    if (ui.content.length === 0) {
                        $(this).addClass('error');
                        var id = $(this).attr('id');
                        var val = $('#flight_originx_temp').val();
                        $('#' + id).val("No result found");
                        setTimeout(function() {
                            $('#' + id).val(val);
                        }, 1000);
                    } else {
                        $(this).removeClass('error');
                    }
                },
                select: function(event, ui) {
                    $(fs + " [name='flight_origin']").val(ui.item.id);
                },
                open: function(e, ui) {
                    var acData = $(this).data("ui-autocomplete");
                    acData.menu.element.find("li a .search_content").each(function() {
                        var me = $(this);
                        var keywords = acData.term.split(" ").join("|");
                        me.html(me.text().replace(new RegExp("(" + keywords + ")", "gi"), "<span class='highlight_text'>$1</span>"));
                    });
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);
            };
        if ($(fs + " .to_flight").length > 0)
            $(fs + " .to_flight").autocomplete({
                source: base_url + "flight/autocomplete",
                minLength: 2,
                response: function(event, ui) {
                    if (ui.content.length === 0) {
                        $(this).addClass("error");
                        var id = $(this).attr('id');
                        var val = $('#flight_destination_temp').val();
                        $('#' + id).val("No result found");
                        setTimeout(function() {
                            $('#' + id).val(val);
                        }, 1000);
                    } else
                        $(this).removeClass("error");
                },
                select: function(event, ui) {
                    $(fs + " [name='flight_destination']").val(ui.item.id);
                },
                open: function(e, ui) {
                    var acData = $(this).data("ui-autocomplete");
                    acData.menu.element.find("li a .search_content").each(function() {
                        var me = $(this);
                        var keywords = acData.term.split(" ").join("|");
                        me.html(me.text().replace(new RegExp("(" + keywords + ")", "gi"), "<span class='highlight_text'>$1</span>"));
                    });
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);
            };
        $(document).on("keypress", fs + " .mflight_origin", function() {
            $(fs + " .mflight_origin ").each(function() {
                $(this).autocomplete({
                    source: base_url + "flight/autocomplete",
                    minLength: 2,
                    response: function(event, ui) {
                        if (ui.content.length === 0) {
                            $(this).addClass('error');
                            $(this).val('');
                            var id = $(this).attr('id');
                            $('#' + id).val("No result found");
                            setTimeout(function() {
                                $('#' + id).val("No result found");
                            }, 1000);
                        } else {
                            $(this).removeClass('error');
                        }
                    },
                    select: function(event, ui) {
                        $(this).closest("div").find("[name='mflight_origin[]']").val(ui.item.id);
                    },
                    open: function(e, ui) {
                        var acData = $(this).data("ui-autocomplete");
                        acData.menu.element.find("li a .search_content").each(function() {
                            var me = $(this);
                            var keywords = acData.term.split(" ").join("|");
                            me.html(me.text().replace(new RegExp("(" + keywords + ")", "gi"), "<span class='highlight_text'>$1</span>"));
                        });
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);
                };
            });
        });
        $(document).on("keypress", fs + " .mflight_destination", function() {
            $(fs + " .mflight_destination").each(function() {
                $(this).autocomplete({
                    source: base_url + "flight/autocomplete",
                    minLength: 2,
                    response: function(event, ui) {
                        if (ui.content.length === 0) {
                            $(this).addClass('error');
                            $(this).val('');
                            var id = $(this).attr('id');
                            $('#' + id).val("No result found");
                            setTimeout(function() {
                                $('#' + id).val("No result found");
                            }, 1000);
                        } else {
                            $(this).removeClass('error');
                        }
                    },
                    select: function(event, ui) {
                        $(this).closest("div").find("[name='mflight_destination[]']").val(ui.item.id);
                    },
                    open: function(e, ui) {
                        var acData = $(this).data("ui-autocomplete");
                        acData.menu.element.find("li a .search_content").each(function() {
                            var me = $(this);
                            var keywords = acData.term.split(" ").join("|");
                            me.html(me.text().replace(new RegExp("(" + keywords + ")", "gi"), "<span class='highlight_text'>$1</span>"));
                        });
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);
                };
            });
        });
        $(document).on("click", fs + " .add_stop_points", function() {
            var multi_flight_cc = $(fs + " .multi_flight").children().length;
            var lang_id = $("#lang_id").val();
            if (lang_id == 1) {
                if (multi_flight_cc < 3) {
                    var counts = $(fs + " .multi_flight").data("counts") === undefined ? 0 : parseInt($(fs + " .multi_flight").data("counts"));
                    count = counts + 2;
                    var multi_flight = $("#multi_flight").clone().removeAttr("id").addClass("more_stop_points");
                    multi_flight.find("input").val("");
                    multi_flight.find("label.error").detach();
                    multi_flight.find(".flight_close").show();
                    multi_flight.find("#mflight_origin1").attr("id", "mflight_origin" + count);
                    multi_flight.find("#mflight_destination1").attr("id", "mflight_destination" + count);
                    multi_flight.find("#mflight_departure1").attr("id", "mflight_departure" + count).removeClass("is-datepick").data("rule-greater", "#mflight_departure1");
                    multi_flight.find("#mflight_departure1_date").attr("id", "mflight_departure" + count + "_date");
                    multi_flight.find(".add_stop_points").removeClass("addflight add_stop_points").addClass("drop_stop_points").attr("id", "remScnt").find("span").html("-");

                    //multi_flight.find("#mflight_departure1_date").remove('#mflight_departure1_date');

                    $(fs + " .multi_flight").append(multi_flight);
                    $(fs + " .multi_flight").data("counts", counts + 1);
                    generate_dp('#flight_search', 'en');

                }

            } else {
                if (multi_flight_cc < 3) {

                    calendar = $.calendars.instance($(this).val());

                    var counts = $(fs + " .multi_flight").data("counts") === undefined ? 0 : parseInt($(fs + " .multi_flight").data("counts"));
                    count = counts + 2;
                    var multi_flight = $("#multi_flight").clone().removeAttr("id").addClass("more_stop_points");
                    multi_flight.find("input").val("");
                    multi_flight.find("label.error").detach();
                    multi_flight.find(".flight_close").show();
                    multi_flight.find("#mflight_origin1").attr("id", "mflight_origin" + count);
                    multi_flight.find("#mflight_destination1").attr("id", "mflight_destination" + count);
                    multi_flight.find("#mflight_departure1").attr("id", "mflight_departure" + count).removeClass("is-datepick").data("rule-greater", "#mflight_departure1");
                    multi_flight.find("#mflight_departure1_date").attr("id", "mflight_departure" + count + "_date");
                    multi_flight.find(".add_stop_points").removeClass("addflight add_stop_points").addClass("drop_stop_points").attr("id", "remScnt").find("span").html("-");
                    //multi_flight.find("#mflight_departure1_date").attr("id","mflight_departure"+count+"_date");
                    $(fs + " .multi_flight").append(multi_flight);
                    $(fs + " .multi_flight").data("counts", counts + 1);
                    generate_dp('#flight_search', 'fa');

                }

            }
        });

        $(document).on("click", ".drop_stop_points", function() {
            $(this).parents(".more_stop_points").detach();
        });
    }
    if ($(dfs).length > 0) {
        $(dfs).validate({
            ignore: ".ignore"
        });
        if ($("select.dom_flight").length > 0) {
            $("select.dom_flight").each(function() {
                var cur_tag = $(this);
                var sco = $(this).data("href") !== undefined ? $(this).data("href") : "";
                var url = base_url + "flight/dom_autocomplete";
                $.ajax({
                    url: url,
                    method: "GET",
                    dataType: "JSON",
                    success: function(response) {
                        cur_tag.html(response.result);
                        if (sco !== "") {
                            cur_tag.val(sco);
                            cur_tag.data("href", "");
                            cur_tag.change();
                        }
                        cur_tag.select2("destroy");
                        cur_tag.select2();
                    },
                    error: function(response) {
                        cur_tag.html("");
                        cur_tag.select2("destroy");
                        cur_tag.select2();
                    }
                });
            });
        }
    }
    $(".active>form .flight_type:checked").trigger("click");
    $(document).on("click", ".flight_type", function() {
        var fs = "#" + $(this).closest("form").attr("id") + " ";
        var id = $(this).data("id");
        if (id === "MC") {
            $(fs + ".normalsearch_div").hide();
            $(fs + ".normalsearch_div :input").attr("disabled", true);
            $(fs + ".multisearch_div").show();
            $(fs + ".multisearch_div input + label.error").detach();
            $(fs + ".multisearch_div :input").removeAttr("disabled").removeClass("error");
            $(fs + ".multi_city_pax_summary").show();
            $(fs + ".multi_city_pax_summary :input").removeAttr("disabled").removeClass("error");
            $(fs + ".oneway_return_pax_summary").hide();
            $(fs + ".oneway_return_pax_summary :input").prop("disabled", true);
            $(fs + ".moreoptions").hide();
        } else if (id === "RT") {
            $(fs + ".returning_div").show();
            $(fs + ".returning_div :input").removeAttr("disabled").removeClass("error");
            $(fs + ".multisearch_div").hide();
            $(fs + ".multisearch_div :input").prop("disabled", true);
            $(fs + ".normalsearch_div").show();
            $(fs + ".normalsearch_div :input").removeAttr("disabled").removeClass("error");
            $(fs + ".normalsearch_div input + label.error").detach();
            $(fs + ".moreoptions").show();
            $(fs + ".departing_div").removeClass("departfull");
            $(fs + ".multi_city_pax_summary").hide();
            $(fs + ".multi_city_pax_summary :input").prop("disabled", true);
            $(fs + ".oneway_return_pax_summary").show();
            $(fs + ".oneway_return_pax_summary :input").removeAttr("disabled").removeClass("error");
            $(fs + ".rarrow").show();
            $(fs + ".oarrow").hide();
        } else {
            $(fs + ".multisearch_div").hide();
            $(fs + ".multisearch_div :input").prop("disabled", true);
            $(fs + ".normalsearch_div").show();
            $(fs + ".normalsearch_div input + label.error").detach();
            $(fs + ".normalsearch_div :input").removeAttr("disabled").removeClass("error");
            $(fs + ".returning_div").hide();
            $(fs + ".returning_div :input").prop("disabled", true);
            $(fs + ".moreoptions").show();
            $(fs + ".departing_div").addClass("departfull");
            $(fs + ".multi_city_pax_summary").hide();
            $(fs + ".multi_city_pax_summary :input").prop("disabled", true);
            $(fs + ".oneway_return_pax_summary").show();
            $(fs + ".oneway_return_pax_summary :input").removeAttr("disabled").removeClass("error");
            $(fs + ".oarrow").show();
            $(fs + ".rarrow").hide();
        }
        generate_dp(fs, language);
    });
    $(".modify_search_details .flight_type:checked").click();
    $("[data-amount]").each(function() {
        var temp = $(this).attr("data-amount");
        $(this).removeAttr("data-amount");
        $(this).data("amount", temp);
        if ($(this).attr("data-travellers") !== undefined) {
            var temp = $(this).attr("data-travellers");
            $(this).removeAttr("data-travellers");
            $(this).data("travellers", temp);
        }
    });
    $(document).on("click", ".view_moreoption", function() {});
    $(document).on("click", ".fare_desc_header", function() {
        $(this).next().slideToggle("slow");
        $(this).toggleClass("expanded");
    });
    $(document).on("click", " .toggle_airlines", function() {
        var is_checked = $(this).is(":checked");
        if (!is_checked)
            $(this).parent().siblings().find("input[type='checkbox']").attr("checked", true).click();
        else
            $(this).parent().siblings().find("input[type='checkbox']").prop("checked", false).click();
    });
    $(document).on("click", " .toggle_airports", function() {
        var is_checked = $(this).is(":checked");
        if (!is_checked)
            $(this).parent().siblings().find("input[type='checkbox']").attr("checked", true).click();
        else
            $(this).parent().siblings().find("input[type='checkbox']").prop("checked", false).click();
    });
    $(document).on("click", ".show_flight", function() {
        var idx = $(this).attr("id").split("_").pop();
        $(document).find("#flightdetails_show_" + idx).slideToggle();
    });
    $(document).on("click", ".modify_flight_search", function() {
        if ($(".modify_flight_search_details").length > 0)
            $(".modify_flight_search_details").slideToggle(500);
    });
    $(document).on("click", ".book_flight, .reserve_flight", function() {
        if ($(this).closest("form.prebook_form").valid()) {
            var idx = $(this).data("fr-id");
            if ($(this).hasClass("flight_confirm")) {
                var form_data = new FormData($("form.prebook_form")[0]);
                var rev_book = $(this).hasClass("reserve_flight");
                if (rev_book)
                    form_data.append("book_type", "reserve");
                else
                    form_data.append("book_type", "book");
                var url = base_url + "payment/book/" + hash + "/" + idx;
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "JSON",
                    data: form_data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        if (rev_book)
                            $(document).find("#bookmodal div:eq(2)").html("Please wait till flight seat is reserved.");
                        else
                            $(document).find("#bookmodal").find("div:eq(2)").html("Please wait till flight seat is booked.");
                        $(document).find("#bookmodal").css({
                            "display": "block"
                        });
                    },
                    success: function(response) {
                        window.location.href = base_url + response.url;
                    },
                    error: function(response) {
                        window.location.href = base_url + "flight/prebook/" + hash + "/" + idx;
                    }
                });
            } else {
                var url = base_url + "flight/revalidate/" + hash + "/" + idx + "/" + 1;
                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(document).find("#revalidatemodal").css({
                            "display": "block"
                        });
                    },
                    success: function(response) {
                        if (response.status === "true") {
                            $(".fancy-title").scrollTop(300);
                            if (response.updated === "true") {
                                $(".notification").html(response.result);
                                $("input[name='travel_insurance']:checked").trigger("change");
                                $(document).find("#revalidatemodal").css({
                                    "display": "none"
                                });
                                setTimeout(function() {
                                    $(".notification .close.alert-close").trigger("click");
                                    window.location.href = base_url + "flight/prebook/" + hash + "/" + idx;
                                }, 5000);
                            } else if (response.redirect === "true") {
                                $(".notification").html(response.result);
                                setTimeOut(function() {
                                    window.location.href = base_url + "flight/lists/" + hash;
                                }, 2000);
                            } else {
                                $(document).find("#revalidatemodal").css({
                                    "display": "none"
                                });
                                $(document).find(".book_flight, .reserve_flight").addClass("flight_confirm").trigger("click");
                            }
                        } else
                            window.location.href = base_url + "flight/lists/" + hash;
                    },
                    error: function(response) {
                        window.location.href = base_url + "flight/prebook/" + hash + "/" + idx;
                    }
                });
            }
        }
    });
    $(document).on("change", "select.set_country", function() {
        var cur_parent_id = $(this).parents(".traveller_div").prop('id');
        var cur_parent = "#" + cur_parent_id;
        if ($(cur_parent).find(".national_id").length > 0 && $(this).val() === "IR") {
            $(cur_parent).find(".national_id").show();
            $(cur_parent).find(".national_id").find(":input").removeAttr("disabled");
            $(cur_parent).find(".passport_num").hide();
            $(cur_parent).find(".passport_num").find(":input").prop("disabled", true);
            $(cur_parent).find(".passport_exp").hide();
            $(cur_parent).find(".passport_exp").find(":input").prop("disabled", true);
            if ($(cur_parent).find(".not_required").length > 0) {
                $(cur_parent).find(".not_required").hide();
                $(cur_parent).find(".not_required").find(":input").prop("disabled", true);
            }
        } else {
            $(cur_parent).find(".national_id").hide();
            $(cur_parent).find(".national_id").find(":input").prop("disabled", true);
            $(cur_parent).find(".passport_num").show();
            $(cur_parent).find(".passport_num").find(":input").removeAttr("disabled");
            $(cur_parent).find(".passport_exp").show();
            $(cur_parent).find(".passport_exp").find(":input").removeAttr("disabled");
            calendar = $.calendars.instance(language);
            global_date_format = (calendar.name === "Persian") ? 'yyyy/mm/dd' : 'dd-mm-yyyy';
            $(cur_parent).find(".from_current_date").datepick({
                minDate: 1,
                changeMonth: true,
                showButtonPanel: true,
                changeYear: true,
                calendar: calendar,
                dateFormat: global_date_format,
                onSelect: function(selected_date) {
                    var js_date = $(this).val();
                    if (selected_date[0]._calendar === $.calendars.instance("fa"))
                        js_date = persianDate_to_Std(selected_date[0]._day, selected_date[0]._month, selected_date[0]._year);
                    if ($(this).closest("div").find("#" + $(this).attr("id") + "_date").length > 0)
                        $(this).closest("div").find("#" + $(this).attr("id") + "_date").val(js_date);
                    else {
                        var str = "<input type=\"hidden\" data-rule-required=\"true\" data-msg-required=\"*\" id=\"" + $(this).attr('id') + "_date\" name=\"" + $(this).attr("name") + "\" value=\"" + js_date + "\" />";
                        $(this).after(str);
                        $(this).removeAttr("name");
                    }
                    if (selected_date[0] !== null) {
                        var dp_name = $(this).attr("name") === undefined ? $("#" + $(this).attr("id") + "_date").attr("name") : $(this).attr("name");
                        var dp_id = $(this).attr("id");
                        var dp_id_alt = dp_id + "_date";
                        if (selected_date[0] !== undefined) {
                            if ($("[name='" + dp_name + "']").length > 1) {
                                set_min_date = false;
                                $("[name='" + dp_name + "']").each(function() {
                                    if ($(this).attr("id") === dp_id || $(this).attr("id") === dp_id_alt)
                                        set_min_date = true;
                                    if (set_min_date && ($(this).attr("id") !== dp_id || $(this).attr("id") !== dp_id))
                                        $(this).datepick("option", {
                                            minDate: selected_date[0]
                                        });
                                });
                            }
                        }
                    }
                }
            });
            if ($(cur_parent).find(".not_required").length > 0) {
                $(cur_parent).find(".not_required").show();
                $(cur_parent).find(".not_required").find(":input").removeAttr("disabled");
            }
        }
    });
    $('.tt').on({
        "click": function() {
            $(this).tooltip({
                items: ".tt",
                content: "<div style='color:red'>Travel insurance details are mentioned here</div>",
                delay: {
                    show: 0,
                    hide: 5000
                }
            });
            $(this).tooltip("open");
        },
        "mouseout": function() {
            $(this).tooltip();
            $(this).tooltip("disable");
        }
    });
    $(document).on("click", ".fare_breakdown", function() {
        $(this).closest(".flight_result").find(".fare_breakdown_details").slideToggle(500);
    });
    $(document).on("submit", "form.discounts", function(e) {
        e.preventDefault();
        if ($(this).valid() === true) {
            var cur_form = $(this);
            var url = base_url + "flight/promocode";
            var total_cost = $(".total_base_cost").data("admin-amount");
            var form_data = new FormData(cur_form[0]);
            form_data.append("amount", total_cost);
            $.ajax({
                url: url,
                method: "POST",
                dataType: "JSON",
                data: form_data,
                processData: false,
                contentType: false,
                success: function(response) {
                    $(document).find(".discount_charges").html(number_format(response.discount)).data("amount", response.discount);
                    $(document).find("input.flight_promocode").val(response.promocode).trigger("change");
                    var s_cls = "error";
                    if (response.status === "true")
                        s_cls = "success";
                    $("<label class='" + s_cls + " promo_status_msg'>" + response.msg + "</label>").insertAfter(cur_form.find("input:eq(0)"));
                    setTimeout(function() {
                        $(document).find(".promo_status_msg").detach();
                    }, 3000);
                },
                error: function(response) {
                    cur_form[0].reset();
                    $(document).find(".discount_charges").html("0").data("amount", "0");
                    $(document).find("input.flight_promocode").val("").trigger("change");
                }
            });
        }
    });
    $(document).on("change", "input[name='travel_insurance'], input[name^='cip_in['], input[name^='cip_out['], select.baggage_select, input.flight_promocode", function() {
        var bag_ins = 0;
        var cipin_ins = 0;
        var cipout_ins = 0;
        var discount = 0;
        var inc_ins = $("input[name='travel_insurance']:checked").val() === "1" ? ($("input[name='travel_insurance']").data("amount") * 1) : 0;
        var total_base_cost = $(".total_base_cost").data("amount") * 1;
        var travellers_count = $(".total_base_cost").data("travellers") * 1;
        var travel_duration = $(".total_base_cost").data("travel-duration") * 1;
        var discount = $(".discount_charges").data("amount") !== undefined ? $(".discount_charges").data("amount") * 1 : 0;
        inc_ins = inc_ins * travellers_count * travel_duration;
        if ($(".baggage_select").length > 0)
            $(".baggage_select option:selected").each(function() {
                bag_ins += $(this).data("amount") * 1;
            });
        $("input[name^='cip_in[']:checked").each(function() {
            cipin_ins += $(this).val() === "1" ? ($(this).data("amount") * 1) : 0;
        });
        $("input[name^='cip_out[']:checked").each(function() {
            cipout_ins += $(this).val() === "1" ? ($(this).data("amount") * 1) : 0;
        });
        cipin_ins = cipin_ins * travellers_count;
        cipout_ins = cipout_ins * travellers_count;
        var additional_charges = bag_ins + inc_ins + cipin_ins + cipout_ins;
        $(".flight_additional_charges").html(number_format(additional_charges, 0));
        $(".baggage_cost").html(number_format(bag_ins));
        $(".travel_insurance_cost").html(number_format(inc_ins));
        $(".cipin_cost").html(number_format(cipin_ins));
        $(".cipout_cost").html(number_format(cipout_ins));
        $(".total_service_cost").html(number_format(additional_charges));
        $(".total_flight_cost").html(number_format(((total_base_cost + additional_charges) - discount), 0));
    });
    //display_farsi(language);
    $('.listpopbtn').click(function() {
        var index = $('.listpopbtn').index(this);
        $('#companion_modal').modal('show');
        $('#companion_modal .companion_select').attr('data-val', index);
    })
    $('.companion_select').click(function() {
        var value = $(this).attr('data-json');
        var index = $(this).attr('data-val');
        var parent = $('.listpopbtn:eq(' + index + ')').closest(".traveller_div").prop('id');
        var cur_parent = "#" + parent;
        if (value !== "") {
            $(cur_parent).find(".passport_num").find(":input").val('');
            $(cur_parent).find(".passport_exp").find(":input").val('');
            $(cur_parent).find(".national_id").find(":input").val('');
            obj = JSON.parse(value);
            var salutationDom = $(cur_parent).find(".salutation").find("select");
            salutationDom.val(obj.salutation);
            salutationDom.change();
            var nationalityDom = $(cur_parent).find(".nationality").find("select");
            nationalityDom.val(obj.nationality);
            nationalityDom.change();
            var d_o_b = $.datepicker.formatDate('dd/mm/yy', new Date(obj.dob));
            var pass_expy = $.datepicker.formatDate('dd/mm/yy', new Date(obj.passport_exp));
            $(cur_parent).find(".fname").find(":input").val(obj.fname);
            $(cur_parent).find(".lname").find(":input").val(obj.lname);
            $(cur_parent).find(".fa_name").find(":input").val(obj.name_fa);
            $(cur_parent).find(".national_id").find(":input").val(obj.national_id);
            $(cur_parent).find(".d_o_b").find(":input").val(d_o_b);
            $(cur_parent).find(".passport_num").find(":input").val(obj.passport_no);
            $(cur_parent).find(".passport_exp").find(":input").val(pass_expy);
        }
        $('#companion_modal').modal('hide');
        $('#flight_prebook_form').validate();
    });
    $('#flight_originx,#flight_destinationx,.inputs_group.from_flight,.inputs_group.to_flight').click(function() {
        $(this).focus();
        $(this).select();
    });
});
$("#from_date").click(function() {
    var origin = $("#flight_originx").val();
    var dest = $("#flight_destinationx").val();
    if (origin == dest) {
        $("#alert_msg").html("Both Source and Destination are same");
        $("#notification_alert").show();
        setTimeout(function() {
            document.getElementsByClassName('alert-close')[0].click();
        }, 5000);
    }
});
$("#adult,#child,#infant").on('change', function() {
    var adult = $('#adult').val();
    var child = $('#child').val();
    var infant = $('#infant').val();
    if (adult == 0 && child == 0 && infant == 0) {
        $("#alert_msg_person").html("please select atleast one peson");
        $("#notification_alert_person").show();
        setTimeout(function() {
            document.getElementsByClassName('alert-close')[1].click();
        }, 5000);
    }
});