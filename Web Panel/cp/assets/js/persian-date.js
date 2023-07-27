/*!
 *
 * persian-date -  0.2.3
 * Reza Babakhani <babakhani.reza@gmail.com>
 * http://babakhani.github.io/PersianWebToolkit/docs/persian-date/
 * Under WTFPL license
 *
 *
 */
(function webpackUniversalModuleDefinition(root, factory) {
    if(typeof exports === 'object' && typeof module === 'object')
        module.exports = factory();
    else if(typeof define === 'function' && define.amd)
        define([], factory);
    else if(typeof exports === 'object')
        exports["persianDate"] = factory();
    else
        root["persianDate"] = factory();
})(this, function() {
    return /******/ (function(modules) { // webpackBootstrap
        /******/ 	// The module cache
        /******/ 	var installedModules = {};
        /******/
        /******/ 	// The require function
        /******/ 	function __webpack_require__(moduleId) {
            /******/
            /******/ 		// Check if module is in cache
            /******/ 		if(installedModules[moduleId])
                /******/ 			return installedModules[moduleId].exports;
            /******/
            /******/ 		// Create a new module (and put it into the cache)
            /******/ 		var module = installedModules[moduleId] = {
                /******/ 			i: moduleId,
                /******/ 			l: false,
                /******/ 			exports: {}
                /******/ 		};
            /******/
            /******/ 		// Execute the module function
            /******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
            /******/
            /******/ 		// Flag the module as loaded
            /******/ 		module.l = true;
            /******/
            /******/ 		// Return the exports of the module
            /******/ 		return module.exports;
            /******/ 	}
        /******/
        /******/
        /******/ 	// expose the modules object (__webpack_modules__)
        /******/ 	__webpack_require__.m = modules;
        /******/
        /******/ 	// expose the module cache
        /******/ 	__webpack_require__.c = installedModules;
        /******/
        /******/ 	// identity function for calling harmony imports with the correct context
        /******/ 	__webpack_require__.i = function(value) { return value; };
        /******/
        /******/ 	// define getter function for harmony exports
        /******/ 	__webpack_require__.d = function(exports, name, getter) {
            /******/ 		if(!__webpack_require__.o(exports, name)) {
                /******/ 			Object.defineProperty(exports, name, {
                    /******/ 				configurable: false,
                    /******/ 				enumerable: true,
                    /******/ 				get: getter
                    /******/ 			});
                /******/ 		}
            /******/ 	};
        /******/
        /******/ 	// getDefaultExport function for compatibility with non-harmony modules
        /******/ 	__webpack_require__.n = function(module) {
            /******/ 		var getter = module && module.__esModule ?
                /******/ 			function getDefault() { return module['default']; } :
                /******/ 			function getModuleExports() { return module; };
            /******/ 		__webpack_require__.d(getter, 'a', getter);
            /******/ 		return getter;
            /******/ 	};
        /******/
        /******/ 	// Object.prototype.hasOwnProperty.call
        /******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
        /******/
        /******/ 	// __webpack_public_path__
        /******/ 	__webpack_require__.p = "";
        /******/
        /******/ 	// Load entry module and return exports
        /******/ 	return __webpack_require__(__webpack_require__.s = 5);
        /******/ })
        /************************************************************************/
        /******/ ([
            /* 0 */
            /***/ (function(module, exports, __webpack_require__) {

                "use strict";


                /**
                 * Constants
                 * @module constants
                 */

                module.exports = {

                    durationUnit: {
                        year: ['y', 'years', 'year'],
                        month: ['M', 'months', 'month'],
                        day: ['d', 'days', 'day'],
                        hour: ['h', 'hours', 'hour'],
                        minute: ['m', 'minutes', 'minute'],
                        second: ['s', 'second', 'seconds'],
                        millisecond: ['ms', 'milliseconds', 'millisecond'],
                        week: ['w', '', 'weeks', 'week']
                    },

                    /**
                     *
                     * @type {number}
                     */
                    GREGORIAN_EPOCH: 1721425.5,

                    /**
                     *
                     * @type {number}
                     */
                    PERSIAN_EPOCH: 1948320.5,

                    /**
                     *
                     * @type {{}}
                     */
                    monthRange: {
                        1: {
                            name: {
                                fa: 'فروردین'
                            },
                            abbr: {
                                fa: 'فرو'
                            }
                        },
                        2: {
                            name: {
                                fa: 'اردیبهشت'
                            },
                            abbr: {
                                fa: 'ارد'
                            }
                        },
                        3: {
                            name: {
                                fa: 'خرداد'
                            },
                            abbr: {
                                fa: 'خرد'
                            }
                        },
                        4: {
                            name: {
                                fa: 'تیر'
                            },
                            abbr: {
                                fa: 'تیر'
                            }
                        },
                        5: {
                            name: {
                                fa: 'مرداد'
                            },
                            abbr: {
                                fa: 'مرد'
                            }
                        },
                        6: {
                            name: {
                                fa: 'شهریور'
                            },
                            abbr: {
                                fa: 'شهر'
                            }
                        },
                        7: {
                            name: {
                                fa: 'مهر'
                            },
                            abbr: {
                                fa: 'مهر'
                            }
                        },
                        8: {
                            name: {
                                fa: 'آبان'
                            },
                            abbr: {
                                fa: 'آبا'
                            }

                        },
                        9: {
                            name: {
                                fa: 'آذر'
                            },
                            abbr: {
                                fa: 'آذر'
                            }
                        },
                        10: {
                            name: {
                                fa: 'دی'
                            },
                            abbr: {
                                fa: 'دی'
                            }
                        },
                        11: {
                            name: {
                                fa: 'بهمن'
                            },
                            abbr: {
                                fa: 'بهم'
                            }
                        },
                        12: {
                            name: {
                                fa: 'اسفند'
                            },
                            abbr: {
                                fa: 'اسف'
                            }
                        }
                    },

                    /**
                     *
                     * @type {{}}
                     */
                    weekRange: {
                        1: {
                            name: {
                                fa: 'شنبه'
                            },
                            abbr: {
                                fa: 'ش'
                            }
                        },
                        2: {
                            name: {
                                fa: 'یکشنبه'
                            },
                            abbr: {
                                fa: 'ی'
                            }
                        },
                        3: {
                            name: {
                                fa: 'دوشنبه'
                            },
                            abbr: {
                                fa: 'د'
                            }
                        },
                        4: {
                            name: {
                                fa: 'سه شنبه'
                            },
                            abbr: {
                                fa: 'س'
                            }
                        },
                        5: {
                            name: {
                                fa: 'چهار شنبه'
                            },
                            abbr: {
                                fa: 'چ'
                            }
                        },
                        6: {
                            name: {
                                fa: 'پنج شنبه'
                            },
                            abbr: {
                                fa: 'پ'
                            }
                        },
                        0: {
                            name: {
                                fa: 'جمعه'
                            },
                            abbr: {
                                fa: 'ج'
                            }
                        }
                    },

                    /**
                     *
                     * @type {string[]}
                     */
                    persianDaysName: ['اورمزد', 'بهمن', 'اوردیبهشت', 'شهریور', 'سپندارمذ', 'خورداد', 'امرداد', 'دی به آذز', 'آذز', 'آبان', 'خورشید', 'ماه', 'تیر', 'گوش', 'دی به مهر', 'مهر', 'سروش', 'رشن', 'فروردین', 'بهرام', 'رام', 'باد', 'دی به دین', 'دین', 'ارد', 'اشتاد', 'آسمان', 'زامیاد', 'مانتره سپند', 'انارام', 'زیادی']
                };

                /***/ }),
            /* 1 */
            /***/ (function(module, exports, __webpack_require__) {

                "use strict";


                var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

                function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

                var durationUnit = __webpack_require__(0).durationUnit;

                var Helpers = function () {
                    function Helpers() {
                        _classCallCheck(this, Helpers);
                    }

                    _createClass(Helpers, [{
                        key: 'toPersianDigit',


                        /**
                         * @description return converted string to persian digit
                         * @param digit
                         * @returns {string|*}
                         */
                        value: function toPersianDigit(digit) {
                            return digit.toString().toPersianDigit();
                        }

                        /**
                         * @param input
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isArray',
                        value: function isArray(input) {
                            return Object.prototype.toString.call(input) === '[object Array]';
                        }

                        /**
                         *
                         * @param input
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isNumber',
                        value: function isNumber(input) {
                            return typeof input === 'number';
                        }

                        /**
                         *
                         * @param input
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isDate',
                        value: function isDate(input) {
                            return input instanceof Date;
                        }

                        /**
                         *
                         * @param input
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isUndefined',
                        value: function isUndefined(input) {
                            return typeof input === 'undefined';
                        }

                        /**
                         * @param number
                         * @param targetLength
                         * @returns {string}
                         */

                    }, {
                        key: 'leftZeroFill',
                        value: function leftZeroFill(number, targetLength) {
                            var output = number + '';
                            while (output.length < targetLength) {
                                output = '0' + output;
                            }
                            return output;
                        }

                        /**
                         * @description normalize duration params and return valid param
                         * @return {{unit: *, value: *}}
                         */

                    }, {
                        key: 'normalizeDuration',
                        value: function normalizeDuration() {
                            var unit = void 0,
                                value = void 0;
                            if (typeof arguments[0] === 'string') {
                                unit = arguments[0];
                                value = arguments[1];
                            } else {
                                value = arguments[0];
                                unit = arguments[1];
                            }
                            if (durationUnit.year.indexOf(unit) > -1) {
                                unit = 'year';
                            } else if (durationUnit.month.indexOf(unit) > -1) {
                                unit = 'month';
                            } else if (durationUnit.day.indexOf(unit) > -1) {
                                unit = 'day';
                            } else if (durationUnit.hour.indexOf(unit) > -1) {
                                unit = 'hour';
                            } else if (durationUnit.minute.indexOf(unit) > -1) {
                                unit = 'minute';
                            } else if (durationUnit.second.indexOf(unit) > -1) {
                                unit = 'second';
                            }
                            return {
                                unit: unit,
                                value: value
                            };
                        }

                        /**
                         *
                         * @param number
                         * @returns {number}
                         */

                    }, {
                        key: 'absRound',
                        value: function absRound(number) {
                            if (number < 0) {
                                return Math.ceil(number);
                            } else {
                                return Math.floor(number);
                            }
                        }

                        /**
                         *
                         * @param a
                         * @param b
                         * @returns {number}
                         */

                    }, {
                        key: 'mod',
                        value: function mod(a, b) {
                            return a - b * Math.floor(a / b);
                        }
                    }]);

                    return Helpers;
                }();

                module.exports = Helpers;

                /***/ }),
            /* 2 */
            /***/ (function(module, exports, __webpack_require__) {

                "use strict";


                var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

                function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

                var Algorithms = __webpack_require__(3);
                var Helpers = __webpack_require__(1);
                var Duration = __webpack_require__(4);
                var toPersianDigit = new Helpers().toPersianDigit;
                var leftZeroFill = new Helpers().leftZeroFill;
                var weekRange = __webpack_require__(0).weekRange;
                var persianDaysName = __webpack_require__(0).persianDaysName;
                var monthRange = __webpack_require__(0).monthRange;

                var PersianDateClass = function () {
                    function PersianDateClass(input) {
                        _classCallCheck(this, PersianDateClass);

                        this.algorithms = new Algorithms();
                        var helpers = new Helpers();
                        // Convert Any thing to Gregorian Date
                        if (helpers.isUndefined(input)) {
                            this.gDate = new Date();
                        } else if (helpers.isDate(input)) {
                            this.gDate = input;
                        } else if (helpers.isArray(input)) {
                            //  Encapsulate Input Array
                            var arrayInput = input.slice();
                            this.gDate = this.algorithms.persianArrayToGregorianDate(arrayInput);
                        } else if (helpers.isNumber(input)) {
                            this.gDate = new Date(input);
                        }
                        // instance of pDate
                        else if (input instanceof PersianDateClass) {
                            this.gDate = input.gDate;
                        }
                        // ASP.NET JSON Date
                        else if (input.substring(0, 6) === '/Date(') {
                            this.gDate = new Date(parseInt(input.substr(6)));
                        } else {
                            this.gDate = new Date();
                        }
                        this.pDate = this.algorithms.toPersianDate(this.gDate);
                        this.version = "0.2.3";
                        this.formatPersian = '_default';
                        this._utcMode = false;
                        return this;
                    }

                    /**
                     * @description return Duration object
                     * @param input
                     * @param key
                     * @returns {Duration}
                     */


                    _createClass(PersianDateClass, [{
                        key: 'duration',
                        value: function duration(input, key) {
                            return new Duration(input, key);
                        }

                        /**
                         * @description check if passed object is duration
                         * @param obj
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isDuration',
                        value: function isDuration(obj) {
                            return obj instanceof Duration;
                        }

                        /**
                         *
                         * @param key
                         * @param input
                         * @returns {PersianDate}
                         */

                    }, {
                        key: 'add',
                        value: function add(key, value) {
                            var duration = new Duration(key, value)._data;
                            // log(duration)
                            if (duration.years > 0) {
                                var newYear = this.year() + duration.years;
                                this.year(newYear);
                            }
                            if (duration.months > 0) {
                                var newMonth = this.month() + duration.months;
                                this.month(newMonth);
                            }
                            if (duration.days > 0) {
                                var newDate = this.date() + duration.days;
                                this.date(newDate);
                            }
                            if (duration.hours > 0) {
                                var newHour = this.hour() + duration.hours;
                                this.hour(newHour);
                            }
                            if (duration.minutes > 0) {
                                var newMinute = this.minute() + duration.minutes;
                                this.minute(newMinute);
                            }
                            if (duration.seconds > 0) {
                                var newSecond = this.second() + duration.seconds;
                                this.second(newSecond);
                            }
                            if (duration.milliseconds > 0) {
                                // log('add millisecond')
                                var newMillisecond = this.milliseconds() + duration.milliseconds;
                                this.milliseconds(newMillisecond);
                            }
                            return new PersianDateClass(this.valueOf());
                        }

                        /**
                         *
                         * @param key
                         * @param input
                         * @returns {PersianDate}
                         */

                    }, {
                        key: 'subtract',
                        value: function subtract(key, value) {
                            var duration = new Duration(key, value)._data;
                            // log(duration)
                            if (duration.years > 0) {
                                var newYear = this.year() - duration.years;
                                this.year(newYear);
                            }
                            if (duration.months > 0) {
                                var newMonth = this.month() - duration.months;
                                this.month(newMonth);
                            }
                            if (duration.days > 0) {
                                var newDate = this.date() - duration.days;
                                this.date(newDate);
                            }
                            if (duration.hours > 0) {
                                var newHour = this.hour() - duration.hours;
                                this.hour(newHour);
                            }
                            if (duration.minutes > 0) {
                                var newMinute = this.minute() - duration.minutes;
                                this.minute(newMinute);
                            }
                            if (duration.seconds > 0) {
                                var newSecond = this.second() - duration.seconds;
                                this.second(newSecond);
                            }
                            if (duration.milliseconds > 0) {
                                // log('add millisecond')
                                var newMillisecond = this.milliseconds() - duration.milliseconds;
                                this.milliseconds(newMillisecond);
                            }
                            return new PersianDateClass(this.valueOf());
                        }

                        /**
                         *
                         * @returns {*}
                         */

                    }, {
                        key: 'formatNumber',
                        value: function formatNumber() {
                            var output = void 0,
                                self = this;

                            // if default conf dosent set follow golbal config
                            if (this.formatPersian === '_default') {
                                if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
                                    /* istanbul ignore next */
                                    if (self.formatPersian === false) {
                                        output = false;
                                    } else {
                                        // Default Conf
                                        output = true;
                                    }
                                }
                                /* istanbul ignore next */
                                else {
                                    if (window.formatPersian === false) {
                                        output = false;
                                    } else {
                                        // Default Conf
                                        output = true;
                                    }
                                }
                            } else {
                                if (this.formatPersian === true) {
                                    output = true;
                                } else if (this.formatPersian === false) {
                                    output = false;
                                } else {
                                    Error('Invalid Config "formatPersian" !!');
                                }
                            }
                            return output;
                        }

                        /**
                         *
                         * @param inputString
                         * @returns {*}
                         */

                    }, {
                        key: 'format',
                        value: function format(inputString) {
                            var self = this,
                                formattingTokens = /([[^[]*])|(\\)?(Mo|MM?M?M?|Do|DD?D?D?|ddddd|dddd?|do?|w[o|w]?|YYYY|YY|a|A|hh?|HH?|mm?|ss?|SS?S?|zz?|ZZ?|X|LT|ll?l?l?|LL?L?L?)/g,
                                info = {
                                    year: self.year(),
                                    month: self.month(),
                                    hour: self.hours(),
                                    minute: self.minutes(),
                                    second: self.seconds(),
                                    date: self.date(),
                                    timezone: self.zone(),
                                    unix: self.unix()
                                },
                                formatToPersian = self.formatNumber();

                            var checkPersian = function checkPersian(i) {
                                if (formatToPersian) {
                                    return toPersianDigit(i);
                                } else {
                                    return i;
                                }
                            };

                            /* jshint ignore:start */
                            function replaceFunction(input) {
                                switch (input) {
                                    // AM/PM
                                    case 'a':
                                    {
                                        if (formatToPersian) return info.hour >= 12 ? 'ب ظ' : 'ق ظ';else return info.hour >= 12 ? 'PM' : 'AM';
                                    }
                                    // Hours (Int)
                                    case 'H':
                                    {
                                        return checkPersian(info.hour);
                                    }
                                    case 'HH':
                                    {
                                        return checkPersian(leftZeroFill(info.hour, 2));
                                    }
                                    case 'h':
                                    {
                                        return checkPersian(info.hour % 12);
                                    }
                                    case 'hh':
                                    {
                                        return checkPersian(leftZeroFill(info.hour % 12, 2));
                                    }
                                    // Minutes
                                    case 'm':
                                    {
                                        return checkPersian(leftZeroFill(info.minute, 2));
                                    }
                                    // Two Digit Minutes
                                    case 'mm':
                                    {
                                        return checkPersian(leftZeroFill(info.minute, 2));
                                    }
                                    // Second
                                    case 's':
                                    {
                                        return checkPersian(info.second);
                                    }
                                    case 'ss':
                                    {
                                        return checkPersian(leftZeroFill(info.second, 2));
                                    }
                                    // Day (Int)
                                    case 'D':
                                    {
                                        return checkPersian(leftZeroFill(info.date));
                                    }
                                    // Return Two Digit
                                    case 'DD':
                                    {
                                        return checkPersian(leftZeroFill(info.date, 2));
                                    }
                                    // Return day Of Month
                                    case 'DDD':
                                    {
                                        var t = self.startOf('year');
                                        return checkPersian(leftZeroFill(self.diff(t, 'days'), 3));
                                    }
                                    // Return Day of Year
                                    case 'DDDD':
                                    {
                                        var _t = self.startOf('year');
                                        return checkPersian(leftZeroFill(self.diff(_t, 'days'), 3));
                                    }
                                    // Return day Of week
                                    case 'd':
                                    {
                                        return checkPersian(self.pDate.weekDayNumber);
                                    }
                                    // Return week day name abbr
                                    case 'ddd':
                                    {
                                        return weekRange[self.pDate.weekDayNumber].abbr.fa;
                                    }
                                    case 'dddd':
                                    {
                                        return weekRange[self.pDate.weekDayNumber].name.fa;
                                    }
                                    // Return Persian Day Name
                                    case 'ddddd':
                                    {
                                        return persianDaysName[self.pDate.monthDayNumber];
                                    }
                                    // Return Persian Day Name
                                    case 'w':
                                    {
                                        var _t2 = self.startOf('year'),
                                            day = parseInt(self.diff(_t2, 'days') / 7) + 1;
                                        return checkPersian(day);
                                    }
                                    // Return Persian Day Name
                                    case 'ww':
                                    {
                                        var _t3 = self.startOf('year'),
                                            _day = leftZeroFill(parseInt(self.diff(_t3, 'days') / 7) + 1, 2);
                                        return checkPersian(_day);
                                    }
                                    // Month  (Int)
                                    case 'M':
                                    {
                                        return checkPersian(info.month);
                                    }
                                    // Two Digit Month (Str)
                                    case 'MM':
                                    {
                                        return checkPersian(leftZeroFill(info.month, 2));
                                    }
                                    // Abbr String of Month (Str)
                                    case 'MMM':
                                    {
                                        return monthRange[info.month].abbr.fa;
                                    }
                                    // Full String name of Month (Str)
                                    case 'MMMM':
                                    {
                                        return monthRange[info.month].name.fa;
                                    }
                                    // Year
                                    // Two Digit Year (Str)
                                    case 'YY':
                                    {
                                        var yearDigitArray = info.year.toString().split('');
                                        return checkPersian(yearDigitArray[2] + yearDigitArray[3]);
                                    }
                                    // Full Year (Int)
                                    case 'YYYY':
                                    {
                                        return checkPersian(info.year);
                                    }
                                    /* istanbul ignore next */
                                    case 'Z':
                                    {
                                        var flag = '+',
                                            hours = Math.round(info.timezone / 60),
                                            minutes = info.timezone % 60;

                                        if (minutes < 0) {
                                            minutes *= -1;
                                        }
                                        if (hours < 0) {
                                            flag = '-';
                                            hours *= -1;
                                        }

                                        var z = flag + leftZeroFill(hours, 2) + ':' + leftZeroFill(minutes, 2);
                                        return checkPersian(z);
                                    }
                                    /* istanbul ignore next */
                                    case 'ZZ':
                                    {
                                        var _flag = '+',
                                            _hours = Math.round(info.timezone / 60),
                                            _minutes = info.timezone % 60;

                                        if (_minutes < 0) {
                                            _minutes *= -1;
                                        }
                                        if (_hours < 0) {
                                            _flag = '-';
                                            _hours *= -1;
                                        }
                                        var _z = _flag + leftZeroFill(_hours, 2) + '' + leftZeroFill(_minutes, 2);
                                        return checkPersian(_z);
                                    }
                                    /* istanbul ignore next */
                                    case 'X':
                                    {
                                        return self.unix();
                                    }
                                    // 8:30 PM
                                    case 'LT':
                                    {
                                        return self.format('h:m a');
                                    }
                                    // 09/04/1986
                                    case 'L':
                                    {
                                        return self.format('YYYY/MM/DD');
                                    }
                                    // 9/4/1986
                                    case 'l':
                                    {
                                        return self.format('YYYY/M/D');
                                    }
                                    // September 4th 1986
                                    case 'LL':
                                    {
                                        return self.format('MMMM DD YYYY');
                                    }
                                    // Sep 4 1986
                                    case 'll':
                                    {
                                        return self.format('MMM DD YYYY');
                                    }
                                    //September 4th 1986 8:30 PM
                                    case 'LLL':
                                    {
                                        return self.format('MMMM YYYY DD   h:m  a');
                                    }
                                    // Sep 4 1986 8:30 PM
                                    case 'lll':
                                    {
                                        return self.format('MMM YYYY DD   h:m  a');
                                    }
                                    //Thursday, September 4th 1986 8:30 PM
                                    case 'LLLL':
                                    {
                                        return self.format('dddd D MMMM YYYY  h:m  a');
                                    }
                                    // Thu, Sep 4 1986 8:30 PM
                                    case 'llll':
                                    {
                                        return self.format('ddd D MMM YYYY  h:m  a');
                                    }
                                }
                            }

                            /* jshint ignore:end */

                            if (inputString) {
                                return inputString.replace(formattingTokens, replaceFunction);
                            } else {
                                var _inputString = 'YYYY-MM-DD HH:mm:ss a';
                                return _inputString.replace(formattingTokens, replaceFunction);
                            }
                        }

                        /**
                         *
                         * @param input
                         * @param val
                         * @param asFloat
                         * @returns {*}
                         */

                    }, {
                        key: 'diff',
                        value: function diff(input, val, asFloat) {
                            var self = this,
                                inputMoment = input,
                                zoneDiff = 0,
                                diff = self.gDate - inputMoment.gDate - zoneDiff,
                                year = self.year() - inputMoment.year(),
                                month = self.month() - inputMoment.month(),
                                date = (self.date() - inputMoment.date()) * -1,
                                output = void 0;

                            if (val === 'months' || val === 'month') {
                                output = year * 12 + month + date / 30;
                            } else if (val === 'years' || val === 'year') {
                                output = year + (month + date / 30) / 12;
                            } else {
                                output = val === 'seconds' || val === 'second' ? diff / 1e3 : // 1000
                                    val === 'minutes' || val === 'minute' ? diff / 6e4 : // 1000 * 60
                                        val === 'hours' || val === 'hour' ? diff / 36e5 : // 1000 * 60 * 60
                                            val === 'days' || val === 'day' ? diff / 864e5 : // 1000 * 60 * 60 * 24
                                                val === 'weeks' || val === 'week' ? diff / 6048e5 : // 1000 * 60 * 60 * 24 * 7
                                                    diff;
                            }
                            if (output < 0) {
                                output = output * -1;
                            }
                            return asFloat ? output : Math.round(output);
                        }

                        /**
                         *
                         * @param key
                         * @returns {*}
                         */

                    }, {
                        key: 'startOf',
                        value: function startOf(key) {
                            // Simplify this\
                            /* jshint ignore:start */
                            switch (key) {
                                case 'years':
                                case 'year':
                                    return new PersianDateClass([this.year(), 1, 1]);
                                case 'months':
                                case 'month':
                                    return new PersianDateClass([this.year(), this.month(), 1]);
                                case 'days':
                                case 'day':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), 0, 0, 0]);
                                case 'hours':
                                case 'hour':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), this.hours(), 0, 0]);
                                case 'minutes':
                                case 'minute':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), this.hours(), this.minutes(), 0]);
                                case 'seconds':
                                case 'second':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), this.hours(), this.minutes(), this.seconds()]);
                                case 'weeks':
                                case 'week':
                                    var weekDayNumber = this.pDate.weekDayNumber;
                                    if (weekDayNumber === 0) {
                                        return new PersianDateClass([this.year(), this.month(), this.date()]);
                                    } else {
                                        return new PersianDateClass([this.year(), this.month(), this.date()]).subtract('days', weekDayNumber);
                                    }
                                default:
                                    return this;
                            }
                            /* jshint ignore:end */
                        }

                        /**
                         *
                         * @param key
                         * @returns {*}
                         */
                        /* eslint-disable no-case-declarations */

                    }, {
                        key: 'endOf',
                        value: function endOf(key) {
                            // Simplify this
                            switch (key) {
                                case 'years':
                                case 'year':
                                    var days = this.isLeapYear() ? 30 : 29;
                                    return new PersianDateClass([this.year(), 12, days, 23, 59, 59]);
                                case 'months':
                                case 'month':
                                    var monthDays = this.daysInMonth(this.year(), this.month());
                                    return new PersianDateClass([this.year(), this.month(), monthDays, 23, 59, 59]);
                                case 'days':
                                case 'day':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), 23, 59, 59]);
                                case 'hours':
                                case 'hour':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), this.hours(), 59, 59]);
                                case 'minutes':
                                case 'minute':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), this.hours(), this.minutes(), 59]);
                                case 'seconds':
                                case 'second':
                                    return new PersianDateClass([this.year(), this.month(), this.date(), this.hours(), this.minutes(), this.seconds()]);
                                case 'weeks':
                                case 'week':
                                    var weekDayNumber = this.pDate.weekDayNumber;
                                    if (weekDayNumber === 6) {
                                        weekDayNumber = 7;
                                    } else {
                                        weekDayNumber = 6 - weekDayNumber;
                                    }
                                    return new PersianDateClass([this.year(), this.month(), this.date()]).add('days', weekDayNumber);
                                default:
                                    return this;
                            }
                            /* eslint-enable no-case-declarations */
                        }

                        /**
                         *
                         * @returns {*}
                         */

                    }, {
                        key: 'sod',
                        value: function sod() {
                            return this.startOf('day');
                        }

                        /**
                         *
                         * @returns {*}
                         */

                    }, {
                        key: 'eod',
                        value: function eod() {
                            return this.endOf('day');
                        }

                        /** Get the timezone offset in minutes.
                         * @return {*}
                         */

                    }, {
                        key: 'zone',
                        value: function zone() {
                            return this.pDate.timeZoneOffset;
                        }

                        /**
                         *
                         * @returns {PersianDate}
                         */

                    }, {
                        key: 'local',
                        value: function local() {
                            var utcStamp = void 0;
                            if (!this._utcMode) {
                                return this;
                            } else {
                                var offsetMils = this.pDate.timeZoneOffset * 60 * 1000;
                                if (this.pDate.timeZoneOffset < 0) {
                                    utcStamp = this.valueOf() - offsetMils;
                                } else {
                                    /* istanbul ignore next */
                                    utcStamp = this.valueOf() + offsetMils;
                                }
                                this.gDate = new Date(utcStamp);
                                this._updatePDate();
                                this._utcMode = false;
                                return this;
                            }
                        }
                    }, {
                        key: 'utc',


                        /**
                         * Current date/time in UTC mode
                         * @param input
                         * @returns {*}
                         */
                        value: function utc(input) {
                            var utcStamp = void 0;
                            if (input) {
                                return new PersianDateClass(input).utc();
                            }
                            if (this._utcMode) {
                                return this;
                            } else {
                                var offsetMils = this.pDate.timeZoneOffset * 60 * 1000;
                                if (this.pDate.timeZoneOffset < 0) {
                                    utcStamp = this.valueOf() + offsetMils;
                                } else {
                                    /* istanbul ignore next */
                                    utcStamp = this.valueOf() - offsetMils;
                                }
                                this.gDate = new Date(utcStamp);
                                this._updatePDate();
                                this._utcMode = true;
                                return this;
                            }
                        }

                        /**
                         *
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isUtc',
                        value: function isUtc() {
                            return this._utcMode;
                        }

                        /**
                         *
                         * @returns {boolean}
                         * version 0.0.1
                         */

                    }, {
                        key: 'isDST',
                        value: function isDST() {
                            var month = this.month(),
                                day = this.date();
                            if (month < 7) {
                                return false;
                            } else if (month == 7 && day >= 2 || month >= 7) {
                                return true;
                            }
                        }

                        /**
                         *
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isLeapYear',
                        value: function isLeapYear() {
                            return this.algorithms.isLeapPersian(this.year());
                        }

                        /**
                         *
                         * @param yearInput
                         * @param monthInput
                         * @returns {number}
                         */

                    }, {
                        key: 'daysInMonth',
                        value: function daysInMonth(yearInput, monthInput) {
                            var year = yearInput ? yearInput : this.year(),
                                month = monthInput ? monthInput : this.month();
                            if (month < 1 || month > 12) return 0;
                            if (month < 7) return 31;
                            if (month < 12) return 30;
                            if (this.algorithms.isLeapPersian(year)) return 30;
                            return 29;
                        }

                        /**
                         * Return Native Javascript Date
                         * @returns {*|PersianDate.gDate}
                         */

                    }, {
                        key: 'toDate',
                        value: function toDate() {
                            return this.gDate;
                        }

                        /**
                         * Returns Array Of Persian Date
                         * @returns {array}
                         */

                    }, {
                        key: 'toArray',
                        value: function toArray() {
                            return [this.year(), this.month(), this.date(), this.hour(), this.minute(), this.second(), this.millisecond()];
                        }

                        /**
                         * Return Milliseconds since the Unix Epoch (1318874398806)
                         * @returns {*}
                         * @private
                         */

                    }, {
                        key: '_valueOf',
                        value: function _valueOf() {
                            return this.gDate.valueOf();
                        }

                        // static unix(timestamp) {
                        //     return this.unix(timestamp);
                        // }


                    }, {
                        key: 'unix',


                        /**
                         * Return Unix Timestamp (1318874398)
                         * @param timestamp
                         * @returns {*}
                         */
                        value: function unix(timestamp) {
                            var output = void 0;
                            if (timestamp) {
                                return new PersianDateClass(timestamp * 1000);
                            } else {
                                var str = this.gDate.valueOf().toString();
                                output = str.substring(0, str.length - 3);
                            }
                            return parseInt(output);
                        }

                        /**
                         *
                         * @param obj
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isPersianDate',
                        value: function isPersianDate(obj) {
                            return obj instanceof PersianDateClass;
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         * Getter Setter
                         */

                    }, {
                        key: 'millisecond',
                        value: function millisecond(input) {
                            return this.milliseconds(input);
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'milliseconds',
                        value: function milliseconds(input) {
                            if (input) {
                                this.gDate.setMilliseconds(input);
                                this._updatePDate();
                                return this;
                            } else {
                                return this.pDate.milliseconds;
                            }
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'second',
                        value: function second(input) {
                            return this.seconds(input);
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'seconds',
                        value: function seconds(input) {
                            if (input | input === 0) {
                                this.gDate.setSeconds(input);
                                this._updatePDate();
                                return this;
                            } else {
                                return this.pDate.seconds;
                            }
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'minute',
                        value: function minute(input) {
                            return this.minutes(input);
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'minutes',
                        value: function minutes(input) {
                            if (input || input === 0) {
                                this.gDate.setMinutes(input);
                                this._updatePDate();
                                return this;
                            } else {
                                // TODO: remove this
                                return parseInt(this.pDate.minutes);
                            }
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'hour',
                        value: function hour(input) {
                            return this.hours(input);
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'hours',
                        value: function hours(input) {
                            if (input | input === 0) {
                                this.gDate.setHours(input);
                                this._updatePDate();
                                return this;
                            } else {
                                return this.pDate.hours;
                            }
                        }

                        /**
                         * Day of Months
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'dates',
                        value: function dates(input) {
                            return this.date(input);
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'date',
                        value: function date(input) {
                            if (input || input === 0) {
                                var pDateArray = this.algorithms.getPersianArrayFromPDate(this.pDate);
                                pDateArray[2] = input;
                                this.gDate = this.algorithms.persianArrayToGregorianDate(pDateArray);
                                this._updatePDate();
                                return this;
                            } else {
                                return this.pDate.date;
                            }
                        }

                        /**
                         * Day of week
                         * @returns {Function|Date.toJSON.day|date_json.day|PersianDate.day|day|output.day|*}
                         */

                    }, {
                        key: 'days',
                        value: function days() {
                            return this.day();
                        }

                        /**
                         *
                         * @returns {Function|Date.toJSON.day|date_json.day|PersianDate.day|day|output.day|*}
                         */

                    }, {
                        key: 'day',
                        value: function day() {
                            return this.pDate.day;
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'month',
                        value: function month(input) {
                            if (input | input === 0) {
                                var pDateArray = this.algorithms.getPersianArrayFromPDate(this.pDate);
                                pDateArray[1] = input;
                                this.gDate = this.algorithms.persianArrayToGregorianDate(pDateArray);
                                this._updatePDate();
                                return this;
                            } else {
                                return this.pDate.month;
                            }
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'years',
                        value: function years(input) {
                            return this.year(input);
                        }

                        /**
                         *
                         * @param input
                         * @returns {*}
                         */

                    }, {
                        key: 'year',
                        value: function year(input) {
                            if (input | input === 0) {
                                var pDateArray = this.algorithms.getPersianArrayFromPDate(this.pDate);
                                pDateArray[0] = input;
                                this.gDate = this.algorithms.persianArrayToGregorianDate(pDateArray);
                                this._updatePDate();
                                return this;
                            } else {
                                return this.pDate.year;
                            }
                        }

                        /**
                         *
                         * @param year
                         * @param month
                         * @returns {*}
                         */

                    }, {
                        key: 'getFirstWeekDayOfMonth',
                        value: function getFirstWeekDayOfMonth(year, month) {
                            var dateArray = this.algorithms.calcPersian(year, month, 1),
                                pdate = this.algorithms.calcGregorian(dateArray[0], dateArray[1], dateArray[2]);
                            if (pdate[3] + 2 === 8) {
                                return 1;
                            } else if (pdate[3] + 2 === 7) {
                                return 7;
                            } else {
                                return pdate[3] + 2;
                            }
                        }

                        /**
                         *
                         * @returns {PersianDate}
                         */

                    }, {
                        key: 'clone',
                        value: function clone() {
                            var self = this;
                            return new PersianDateClass(self.gDate);
                        }

                        /**
                         *
                         * @private
                         */

                    }, {
                        key: '_updatePDate',
                        value: function _updatePDate() {
                            this.pDate = this.algorithms.toPersianDate(this.gDate);
                        }

                        /**
                         *
                         * @returns {*}
                         */

                    }, {
                        key: 'valueOf',
                        value: function valueOf() {
                            return this._valueOf();
                        }
                    }], [{
                        key: '_utc',
                        value: function _utc(input) {
                            if (input) {
                                return new PersianDateClass(input).utc();
                            } else {
                                return new PersianDateClass().utc();
                            }
                        }
                    }, {
                        key: '_unix',
                        value: function _unix(timestamp) {
                            if (timestamp) {
                                return new PersianDateClass(timestamp * 1000).unix();
                            } else {
                                return new PersianDateClass().unix();
                            }
                        }
                    }]);

                    return PersianDateClass;
                }();

                module.exports = PersianDateClass;

                /***/ }),
            /* 3 */
            /***/ (function(module, exports, __webpack_require__) {

                "use strict";


                var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

                function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

                var GREGORIAN_EPOCH = __webpack_require__(0).GREGORIAN_EPOCH;
                var PERSIAN_EPOCH = __webpack_require__(0).PERSIAN_EPOCH;
                var Helpers = __webpack_require__(1);
                var mod = new Helpers().mod;

                /**
                 * @description Calendar algorithms implementations
                 * @author Reza Babakhani
                 */

                var Algorithms = function () {
                    function Algorithms() {
                        _classCallCheck(this, Algorithms);
                    }

                    _createClass(Algorithms, [{
                        key: 'jwday',

                        /**
                         * @param j
                         * @returns {*}
                         */
                        value: function jwday(j) {
                            var mod = function mod(a, b) {
                                return a - b * Math.floor(a / b);
                            };
                            return mod(Math.floor(j + 1.5), 7);
                        }

                        /**
                         * @description Is a given year in the Gregorian calendar a leap year ?
                         * @param year
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isLeapGregorian',
                        value: function isLeapGregorian(year) {
                            return year % 4 === 0 && !(year % 100 === 0 && year % 400 !== 0);
                        }

                        /**
                         * @param year
                         * @returns {boolean}
                         */

                    }, {
                        key: 'isLeapPersian',
                        value: function isLeapPersian(year) {
                            return ((year - (year > 0 ? 474 : 473)) % 2820 + 474 + 38) * 682 % 2816 < 682;
                            // https://fa.wikipedia.org/wiki/%DA%AF%D8%A7%D9%87%E2%80%8C%D8%B4%D9%85%D8%A7%D8%B1%DB%8C_%D9%87%D8%AC%D8%B1%DB%8C_%D8%AE%D9%88%D8%B1%D8%B4%DB%8C%D8%AF%DB%8C_%D8%AD%D8%B3%D8%A7%D8%A8%DB%8C
                            // return parseFloat('0.' + ((year + 2346) * (0.24219858156)).toString().split('.')[1]) < 0.24219858156;
                        }

                        /**
                         * Determine Julian day number from Gregorian calendar date
                         * @param year
                         * @param month
                         * @param day
                         * @returns {number}
                         */

                    }, {
                        key: 'gregorianToJd',
                        value: function gregorianToJd(year, month, day) {
                            return GREGORIAN_EPOCH - 1 + 365 * (year - 1) + Math.floor((year - 1) / 4) + -Math.floor((year - 1) / 100) + Math.floor((year - 1) / 400) + Math.floor((367 * month - 362) / 12 + (month <= 2 ? 0 : this.isLeapGregorian(year) ? -1 : -2) + day);
                        }

                        /**
                         * Calculate Gregorian calendar date from Julian day
                         * @param jd
                         * @returns {Array}
                         */

                    }, {
                        key: 'jdToGregorian',
                        value: function jdToGregorian(jd) {
                            //let wjd, depoch, quadricent, dqc, cent, dcent, quad, dquad, yindex, dyindex, year, yearday, leapadj;
                            var wjd = Math.floor(jd - 0.5) + 0.5,
                                depoch = wjd - GREGORIAN_EPOCH,
                                quadricent = Math.floor(depoch / 146097),
                                dqc = mod(depoch, 146097),
                                cent = Math.floor(dqc / 36524),
                                dcent = mod(dqc, 36524),
                                quad = Math.floor(dcent / 1461),
                                dquad = mod(dcent, 1461),
                                yindex = Math.floor(dquad / 365),
                                year = quadricent * 400 + cent * 100 + quad * 4 + yindex;
                            if (!(cent == 4 || yindex == 4)) {
                                year++;
                            }
                            var yearday = wjd - this.gregorianToJd(year, 1, 1),
                                leapadj = wjd < this.gregorianToJd(year, 3, 1) ? 0 : this.isLeapGregorian(year) ? 1 : 2,
                                month = Math.floor(((yearday + leapadj) * 12 + 373) / 367),
                                day = wjd - this.gregorianToJd(year, month, 1) + 1;
                            return new Array(year, month, day);
                        }

                        /**
                         * Determine Julian day from Persian date
                         * @param year
                         * @param month
                         * @param day
                         * @returns {*}
                         */

                    }, {
                        key: 'persianToJd',
                        value: function persianToJd(year, month, day) {
                            var epbase = void 0,
                                epyear = void 0;
                            epbase = year - (year >= 0 ? 474 : 473);
                            epyear = 474 + mod(epbase, 2820);
                            return day + (month <= 7 ? (month - 1) * 31 : (month - 1) * 30 + 6) + Math.floor((epyear * 682 - 110) / 2816) + (epyear - 1) * 365 + Math.floor(epbase / 2820) * 1029983 + (PERSIAN_EPOCH - 1);
                        }

                        /**
                         * Calculate Persian date from Julian day
                         * @param jd
                         * @returns {Array}
                         */

                    }, {
                        key: 'jdToPersian',
                        value: function jdToPersian(jd) {
                            var year = void 0,
                                month = void 0,
                                day = void 0,
                                depoch = void 0,
                                cycle = void 0,
                                cyear = void 0,
                                ycycle = void 0,
                                aux1 = void 0,
                                aux2 = void 0,
                                yday = void 0;
                            jd = Math.floor(jd) + 0.5;
                            depoch = jd - this.persianToJd(475, 1, 1);
                            cycle = Math.floor(depoch / 1029983);
                            cyear = mod(depoch, 1029983);
                            if (cyear === 1029982) {
                                /* istanbul ignore next */
                                ycycle = 2820;
                            } else {
                                aux1 = Math.floor(cyear / 366);
                                aux2 = mod(cyear, 366);
                                ycycle = Math.floor((2134 * aux1 + 2816 * aux2 + 2815) / 1028522) + aux1 + 1;
                            }
                            year = ycycle + 2820 * cycle + 474;
                            if (year <= 0) {
                                year -= 1;
                            }
                            yday = jd - this.persianToJd(year, 1, 1) + 1;
                            month = yday <= 186 ? Math.ceil(yday / 31) : Math.ceil((yday - 6) / 30);
                            day = jd - this.persianToJd(year, month, 1) + 1;
                            return new Array(year, month, day);
                        }

                        /**
                         *
                         * @param year
                         * @param month
                         * @param day
                         * @returns {Array}
                         */

                    }, {
                        key: 'calcPersian',
                        value: function calcPersian(year, month, day) {
                            var j = this.persianToJd(year, month, day),
                                date = this.jdToGregorian(j);
                            return new Array(date[0], date[1] - 1, date[2]);
                        }

                        /**
                         * Perform calculation starting with a Gregorian date
                         * @param year
                         * @param month
                         * @param day
                         * @returns {Array}
                         */

                    }, {
                        key: 'calcGregorian',
                        value: function calcGregorian(year, month, day) {
                            //  Update Julian day
                            var j = this.gregorianToJd(year, month + 1, day) + Math.floor(0 + 60 * (0 + 60 * 0) + 0.5) / 86400.0,

                                //  Update Persian Calendar
                                perscal = this.jdToPersian(j),
                                weekday = this.jwday(j);
                            return new Array(perscal[0], perscal[1], perscal[2], weekday);
                        }

                        /**
                         * Converts a gregorian date to Jalali date for different formats
                         * @param gd
                         * @returns {{}}
                         */

                    }, {
                        key: 'toPersianDate',
                        value: function toPersianDate(gd) {
                            var pa = this.calcGregorian(gd.getFullYear(), gd.getMonth(), gd.getDate()),
                                output = {};
                            output.monthDayNumber = pa[2] - 1;
                            if (pa[3] == 6) {
                                output.weekDayNumber = 1;
                            } else if (pa[3] === 5) {
                                output.weekDayNumber = 0;
                            } else if (pa[3] === 4) {
                                output.weekDayNumber = 6;
                            } else if (pa[3] === 3) {
                                output.weekDayNumber = 5;
                            } else if (pa[3] === 2) {
                                output.weekDayNumber = 4;
                            } else if (pa[3] === 1) {
                                output.weekDayNumber = 3;
                            } else if (pa[3] === 0) {
                                output.weekDayNumber = 2;
                            }
                            output.year = pa[0];
                            output.month = pa[1];
                            output.day = output.weekDayNumber;
                            output.date = pa[2];
                            output.hours = gd.getHours();
                            output.minutes = gd.getMinutes() < 10 ? '0' + gd.getMinutes() : gd.getMinutes();
                            output.seconds = gd.getSeconds();
                            output.milliseconds = gd.getMilliseconds();
                            output.timeZoneOffset = gd.getTimezoneOffset();
                            return output;
                        }

                        /**
                         *
                         * @param parray persian-date array
                         * @returns {Date}
                         */

                    }, {
                        key: 'persianArrayToGregorianDate',
                        value: function persianArrayToGregorianDate(parray) {
                            if (parray[1] === undefined) {
                                parray[1] = 1;
                            }
                            if (parray[2] === undefined) {
                                parray[2] = 1;
                            }
                            // Howha : javascript Cant Parse this array truly 2011,2,20
                            var pd = this.calcPersian(parray[0], parray[1], parray[2]),
                                gDate = new Date();
                            gDate.setYear(pd[0]);
                            gDate.setMonth(pd[1]);
                            gDate.setDate(pd[2]);
                            gDate.setHours(parray[3] ? parray[3] : 0);
                            gDate.setMinutes(parray[4] ? parray[4] : 0);
                            gDate.setSeconds(parray[5] ? parray[5] : 0);
                            gDate.setMilliseconds(parray[6] ? parray[6] : 0);
                            return gDate;
                        }

                        /**
                         *
                         * @param pDate
                         * @returns {array}
                         */

                    }, {
                        key: 'getPersianArrayFromPDate',
                        value: function getPersianArrayFromPDate(pDate) {
                            return [pDate.year, pDate.month, pDate.date, pDate.hours, pDate.minutes, pDate.seconds, pDate.milliseconds];
                        }
                    }]);

                    return Algorithms;
                }();

                module.exports = Algorithms;

                /***/ }),
            /* 4 */
            /***/ (function(module, exports, __webpack_require__) {

                "use strict";


                function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

                var Helpers = __webpack_require__(1);
                var normalizeDuration = new Helpers().normalizeDuration;
                var absRound = new Helpers().absRound;
                /**
                 * Duration object constructor
                 * @param duration
                 * @class Duration
                 * @constructor
                 */

                var Duration = function Duration(key, value) {
                    _classCallCheck(this, Duration);

                    var duration = {},
                        data = this._data = {},
                        milliseconds = 0,
                        normalizedUnit = normalizeDuration(key, value),
                        unit = normalizedUnit.unit;
                    duration[unit] = normalizedUnit.value;
                    milliseconds = duration.milliseconds || duration.millisecond || duration.ms || 0;

                    var years = duration.years || duration.year || duration.y || 0,
                        months = duration.months || duration.month || duration.M || 0,
                        weeks = duration.weeks || duration.w || duration.week || 0,
                        days = duration.days || duration.d || duration.day || 0,
                        hours = duration.hours || duration.hour || duration.h || 0,
                        minutes = duration.minutes || duration.minute || duration.m || 0,
                        seconds = duration.seconds || duration.second || duration.s || 0;
                    // representation for dateAddRemove
                    this._milliseconds = milliseconds + seconds * 1e3 + minutes * 6e4 + hours * 36e5;
                    // Because of dateAddRemove treats 24 hours as different from a
                    // day when working around DST, we need to store them separately
                    this._days = days + weeks * 7;
                    // It is impossible translate months into days without knowing
                    // which months you are are talking about, so we have to store
                    // it separately.
                    this._months = months + years * 12;
                    // The following code bubbles up values, see the tests for
                    // examples of what that means.
                    data.milliseconds = milliseconds % 1000;
                    seconds += milliseconds / 1000;
                    data.seconds = seconds % 60;
                    minutes += absRound(seconds / 60);
                    data.minutes = minutes % 60;
                    hours += absRound(minutes / 60);
                    data.hours = hours % 24;
                    days += absRound(hours / 24);
                    days += weeks * 7;
                    data.days = days % 30;
                    months += absRound(days / 30);
                    data.months = months % 12;
                    years += absRound(months / 12);
                    data.years = years;
                    return this;
                };

                /**
                 *
                 * @type {{valueOf: Duration.valueOf}}
                 */


                Duration.prototype = {
                    valueOf: function valueOf() {
                        return this._milliseconds + this._days * 864e5 + this._months * 2592e6;
                    }
                };

                module.exports = Duration;

                /***/ }),
            /* 5 */
            /***/ (function(module, exports, __webpack_require__) {

                "use strict";


                var PersianDateClass = __webpack_require__(2);
                String.prototype.toPersianDigit = function (latinDigit) {
                    return this.replace(/\d+/g, function (digit) {
                        var enDigitArr = [],
                            peDigitArr = [],
                            i = void 0,
                            j = void 0;
                        for (i = 0; i < digit.length; i += 1) {
                            enDigitArr.push(digit.charCodeAt(i));
                        }
                        for (j = 0; j < enDigitArr.length; j += 1) {
                            peDigitArr.push(String.fromCharCode(enDigitArr[j] + (!!latinDigit && latinDigit === true ? 1584 : 1728)));
                        }
                        return peDigitArr.join('');
                    });
                };
                PersianDateClass.unix = PersianDateClass._unix;
                PersianDateClass.utc = PersianDateClass._utc;

                module.exports = PersianDateClass;

                /***/ })
            /******/ ]);
});