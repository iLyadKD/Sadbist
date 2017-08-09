///* 

// Persian Calendar


function persianDate_to_Eng(dd, mm, yy) {


    var MonName = "";
    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);
    datearr = calcPersian(yy, mm, dd);
    MonName = GetMonthNamePersian(parseInt(datearr[1]));
    return datearr[2] + " " + MonName + " " + datearr[0];

}

function persianDate_to_Std(dd, mm, yy) {


    var MonName = "";
    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);
    datearr = calcPersian(yy, mm, dd);
    return datearr[2] + "-" + datearr[1].toString().str_pad_left("0", 2) + "-" + datearr[0].toString().str_pad_left("0", 2);

}

function persianDate_to_Eng_MonthDigit(dd, mm, yy) {


    var MonName = "";
    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);
    datearr = calcPersian(yy, mm, dd);
    return datearr[2] + " " + datearr[1] + " " + datearr[0];

}





function EngDate_to_persian(dd, mm, yy) {

    if (mm.length > 2) {
        mm = GetMonthNoPersian(mm);
    }

    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);

    datearr = calcGregorian(yy, mm, dd);
    datearr[0] = GetPersianYear(datearr[0].toString());
    datearr[1] = getPersianMonthName(datearr[1].toString());
    datearr[2] = GetPersianDay(datearr[2].toString());

    return datearr[0] + "/" + datearr[1] + "/" + datearr[2];

}



function StdDate_to_persian(dd, mm, yy) {

    if (mm.length > 2) {
        mm = GetMonthNoPersian(mm);
    }

    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);

    datearr = calcGregorian(yy, mm, dd);
    datearr[0] = GetPersianYear(datearr[0].toString());
    datearr[1] = getPersianMonthName(datearr[1].toString());
    datearr[2] = GetPersianDay(datearr[2].toString());
    datearr[3] = numToPerDayName(datearr[3]);

    return datearr[3] + ", " + datearr[0] + " " + datearr[1] + " " + datearr[2];

}

function StdDate_to_JSDate(dd, mm, yy) {

    if (mm.length > 2) {
        mm = GetMonthNoPersian(mm);
    }

    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);

    datearr = calcGregorian(yy, mm, dd);
    // datearr[0] = GetPersianYear(datearr[0].toString());
    datearr[1] = GetMonthNamePersian(datearr[1].toString());
    // datearr[2] = GetPersianDay(datearr[2].toString());
    datearr[3] = numToDayName(datearr[3]);

    return datearr[3] + ", " + datearr[0] + " " + datearr[1] + " " + datearr[2];

}

function EngDate_to_persian_EngDigit(dd, mm, yy) {

    if (mm.length > 2) {
        mm = GetMonthNoPersian(mm);
    }

    mm = parseInt(mm);
    dd = parseInt(dd);
    yy = parseInt(yy);
    datearr = calcGregorian(yy, mm, dd);
    return datearr[0] + "/" + datearr[1] + "/" + datearr[2];
}




function GetMonthNoPersian(m) {
    var Mno;
    switch (m.toLowerCase()) {
        case 'jan':
            Mno = "1";
            break;
        case 'feb':
            Mno = "2";
            break;
        case 'mar':
            Mno = "3";
            break;
        case 'apr':
            Mno = "4";
            break;
        case 'may':
            Mno = "5";
            break;
        case 'jun':
            Mno = "6";
            break;

        case 'jul':
            Mno = "7";
            break;

        case 'aug':
            Mno = "8";
            break;

        case 'sep':
            Mno = "9";
            break;

        case 'oct':
            Mno = "10";
            break;

        case 'nov':
            Mno = "11";
            break;

        case 'dec':
            Mno = "12";
            break;

    }
    return Mno;


}


function GetMonthNamePersian(m) {
    var Mno;
    switch (parseInt(m)) {
        case 1:
            Mno = "Jan";
            break;
        case 2:
            Mno = "Feb";
            break;
        case 3:
            Mno = "Mar";
            break;
        case 4:
            Mno = "Apr";
            break;
        case 5:
            Mno = 'May';
            break;
        case 6:
            Mno = "Jun";
            break;

        case 7:
            Mno = "Jul";
            break;

        case 8:
            Mno = "Aug";
            break;

        case 9:
            Mno = "Sep";
            break;

        case 10:
            Mno = "Oct";
            break;

        case 11:
            Mno = "Nov";
            break;

        case 12:
            Mno = 'Dec';
            break;

    }

    return Mno;
}






function mod(a, b) {
    return a - (b * Math.floor(a / b));
}

function jwday(j) {
    return mod(Math.floor((j + 1.5)), 7);
}

var Weekdays = new Array("Sunday", "Monday", "Tuesday", "Wednesday",
                          "Thursday", "Friday", "Saturday");

//  LEAP_GREGORIAN  --  Is a given year in the Gregorian calendar a leap year ?

function leap_gregorian(year) {
    return ((year % 4) == 0) &&
            (!(((year % 100) == 0) && ((year % 400) != 0)));
}

//  GREGORIAN_TO_JD  --  Determine Julian day number from Gregorian calendar date

var GREGORIAN_EPOCH = 1721425.5;

function gregorian_to_jd(year, month, day) {
    return (GREGORIAN_EPOCH - 1) +
           (365 * (year - 1)) +
           Math.floor((year - 1) / 4) +
           (-Math.floor((year - 1) / 100)) +
           Math.floor((year - 1) / 400) +
           Math.floor((((367 * month) - 362) / 12) +
           ((month <= 2) ? 0 :
                               (leap_gregorian(year) ? -1 : -2)
           ) +
           day);
}

//  JD_TO_GREGORIAN  --  Calculate Gregorian calendar date from Julian day

function jd_to_gregorian(jd) {
    var wjd, depoch, quadricent, dqc, cent, dcent, quad, dquad,
        yindex, dyindex, year, yearday, leapadj;

    wjd = Math.floor(jd - 0.5) + 0.5;
    depoch = wjd - GREGORIAN_EPOCH;
    quadricent = Math.floor(depoch / 146097);
    dqc = mod(depoch, 146097);
    cent = Math.floor(dqc / 36524);
    dcent = mod(dqc, 36524);
    quad = Math.floor(dcent / 1461);
    dquad = mod(dcent, 1461);
    yindex = Math.floor(dquad / 365);
    year = (quadricent * 400) + (cent * 100) + (quad * 4) + yindex;
    if (!((cent == 4) || (yindex == 4))) {
        year++;
    }
    yearday = wjd - gregorian_to_jd(year, 1, 1);
    leapadj = ((wjd < gregorian_to_jd(year, 3, 1)) ? 0
                                                  :
                  (leap_gregorian(year) ? 1 : 2)
              );
    month = Math.floor((((yearday + leapadj) * 12) + 373) / 367);
    day = (wjd - gregorian_to_jd(year, month, 1)) + 1;

    return new Array(year, month, day);
}

//  LEAP_PERSIAN  --  Is a given year a leap year in the Persian calendar ?

function leap_persian(year) {
    return ((((((year - ((year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;
}

//  PERSIAN_TO_JD  --  Determine Julian day from Persian date

var PERSIAN_EPOCH = 1948320.5;
var PERSIAN_WEEKDAYS = new Array("í˜ÔäÈå", "ÏæÔäÈå",
                                 "Óå ÔäÈå", "åÇÑÔäÈå",
                                 "äÌ ÔäÈå", "ÌãÚå", "ÔäÈå");

function persian_to_jd(year, month, day) {
    var epbase, epyear;

    epbase = year - ((year >= 0) ? 474 : 473);
    epyear = 474 + mod(epbase, 2820);

    return day +
            ((month <= 7) ?
                ((month - 1) * 31) :
                (((month - 1) * 30) + 6)
            ) +
            Math.floor(((epyear * 682) - 110) / 2816) +
            (epyear - 1) * 365 +
            Math.floor(epbase / 2820) * 1029983 +
            (PERSIAN_EPOCH - 1);
}

//  JD_TO_PERSIAN  --  Calculate Persian date from Julian day

function jd_to_persian(jd) {
    var year, month, day, depoch, cycle, cyear, ycycle,
        aux1, aux2, yday;


    jd = Math.floor(jd) + 0.5;

    depoch = jd - persian_to_jd(475, 1, 1);
    cycle = Math.floor(depoch / 1029983);
    cyear = mod(depoch, 1029983);
    if (cyear == 1029982) {
        ycycle = 2820;
    } else {
        aux1 = Math.floor(cyear / 366);
        aux2 = mod(cyear, 366);
        ycycle = Math.floor(((2134 * aux1) + (2816 * aux2) + 2815) / 1028522) +
                    aux1 + 1;
    }
    year = ycycle + (2820 * cycle) + 474;
    if (year <= 0) {
        year--;
    }
    yday = (jd - persian_to_jd(year, 1, 1)) + 1;
    month = (yday <= 186) ? Math.ceil(yday / 31) : Math.ceil((yday - 6) / 30);
    day = (jd - persian_to_jd(year, month, 1)) + 1;
    return new Array(year, month, day);
}

function calcPersian(year, month, day) {
    var date, j;

    j = persian_to_jd(year, month, day);
    date = jd_to_gregorian(j);
    weekday = jwday(j);
    return new Array(date[0], date[1], date[2], weekday);
}

//  calcGregorian  --  Perform calculation starting with a Gregorian date
function calcGregorian(year, month, day) {
    month--;

    var j, weekday;

    //  Update Julian day

    j = gregorian_to_jd(year, month + 1, day) +
           (Math.floor(0 + 60 * (0 + 60 * 0) + 0.5) / 86400.0);

    //  Update Persian Calendar
    perscal = jd_to_persian(j);
    weekday = jwday(j);
    return new Array(perscal[0], perscal[1], perscal[2], weekday);
}

function getTodayGregorian() {
    var t = new Date();
    var today = new Date();

    var y = today.getYear();
    if (y < 1000) {
        y += 1900;
    }

    return new Array(y, today.getMonth() + 1, today.getDate(), t.getDay());
}

function getTodayPersian() {
    var t = new Date();
    var today = getTodayGregorian();
    var persian = calcGregorian(today[0], today[1], today[2]);
    return new Array(persian[0], persian[1], persian[2], t.getDay());
}


function GetPersianDay(engdg) {

    var prdg = "";
    switch (parseInt(engdg)) {
        case 0:
            prdg = "0"
            break;
        case 1:
            prdg = "1"
            break;
        case 2:
            prdg = "2"
            break;
        case 3:
            prdg = "3"
            break;
        case 4:
            prdg = "4"
            break;
        case 5:
            prdg = "5"
            break;
        case 6:
            prdg = "6"
            break;
        case 7:
            prdg = "7"
            break;
        case 8:
            prdg = "8"
            break;
        case 9:
            prdg = "9"
            break;

        case 10:
            prdg = "10"
            break;
        case 11:
            prdg = "11"
            break;
        case 12:
            prdg = "12"
            break;
        case 13:
            prdg = "13"
            break;
        case 14:
            prdg = "14"
            break;
        case 15:
            prdg = "15"
            break;
        case 16:
            prdg = "16"
            break;
        case 17:
            prdg = "17"
            break;
        case 18:
            prdg = "18"
            break;
        case 19:
            prdg = "19"
            break;

        case 20:
            prdg = "20"
            break;
        case 21:
            prdg = "21"
            break;
        case 22:
            prdg = "22"
            break;
        case 23:
            prdg = "23"
            break;
        case 24:
            prdg = "24"
            break;
        case 25:
            prdg = "25"
            break;
        case 26:
            prdg = "26"
            break;
        case 27:
            prdg = "27"
            break;
        case 28:
            prdg = "28"
            break;
        case 29:
            prdg = "29"
            break;
        case 30:
            prdg = "30"
            break;
        case 31:
            prdg = "31"
            break;

    }
    return prdg;
}


function getperDayDigit(perdig) {


    var prdg = "";
    switch (perdig) {
        case "0":
            prdg = "0"
            break;
        case "1":
            prdg = "1"
            break;
        case "2":
            prdg = "2"
            break;
        case "3":
            prdg = "3"
            break;
        case "4":
            prdg = "4"
            break;
        case "5":
            prdg = "5"
            break;
        case "6":
            prdg = "6"
            break;
        case "7":
            prdg = "7"
            break;
        case "8":
            prdg = "8"
            break;
        case "9":
            prdg = "9"
            break;
        case "10":
            prdg = "10"
            break;
        case "11":
            prdg = "11"
            break;
        case "12":
            prdg = "12"
            break;
        case "13":
            prdg = "13"
            break;
        case "14":
            prdg = "14"
            break;
        case "15":
            prdg = " 15"
            break;
        case "16":
            prdg = "16"
            break;
        case "17":
            prdg = "17"
            break;
        case "18":
            prdg = "18"
            break;
        case "19":
            prdg = "19"
            break;
        case "20":
            prdg = "20"
            break;
        case "21":
            prdg = "21";
            break;
        case "22":
            prdg = "22";
            break;
        case "23":
            prdg = "23"
            break;
        case "24":
            prdg = "24"
            break;
        case "25":
            prdg = "25"
            break;
        case "26":
            prdg = "26"
            break;
        case "27":
            prdg = "27"
            break;
        case "28":
            prdg = "28"
            break;
        case "29":
            prdg = "29"
            break;
        case "30":
            prdg = "30"
            break;
        case "31":
            prdg = "31"
            break;
    }
    return prdg;
}




function getPerMonthDigit(monthNo) {
    prMonName = "";

    switch (monthNo) {
        case "فروردین":
            prMonName = "01"
            break;
        case "اردیبهشت":
            prMonName = "02"
            break;
        case "خرداد":
            prMonName = "03"
            break;
        case "تیر":
            prMonName = "04"
            break;
        case "مرداد":
            prMonName = "05"
            break;

        case "شهریور":
            prMonName = "06"
            break;

        case "مهر":
            prMonName = "07"
            break;

        case "آبان":
            prMonName = "08"
            break;
        case "آذر":
            prMonName = "09"
            break;
        case "دی":
            prMonName = "10"
            break;
        case "بهمن":
            prMonName = "11"
            break;
        case "اسفند":
            prMonName = "12"
            break;
    }

    return prMonName

}

//function getPerMonthDigit(monthNo) {
//    prMonName = "";

//    switch (monthNo) {
//        case "فَروَردین":
//            prMonName = "01"
//            break;
//        case "اردیبِهِشت":
//            prMonName = "02"
//            break;
//        case "خُرداد":
//            prMonName = "03"
//            break;
//        case "تیر":
//            prMonName = "04"
//            break;
//        case "مُرداد":
//            prMonName = "05"
//            break;

//        case "شَهریوَر":
//            prMonName = "06"
//            break;

//        case "مِهر":
//            prMonName = "07"
//            break;

//        case "آبان":
//            prMonName = "08"
//            break;
//        case "آذَر":
//            prMonName = "09"
//            break;
//        case "دَی":
//            prMonName = "10"
//            break;
//        case "بَهمَن":
//            prMonName = "11"
//            break;
//        case "اِسفَند":
//            prMonName = "12"
//            break;
//    }

//    return prMonName

//}


function getPersianMonthName(monthNo) {
    prMonName = "";

    switch (parseInt(monthNo)) {
        case 1:
            prMonName = "فروردین"
            break;
        case 2:
            prMonName = "اردیبهشت"
            break;
        case 3:
            prMonName = "خرداد"
            break;
        case 4:
            prMonName = "تیر"
            break;
        case 5:
            prMonName = "مرداد"
            break;

        case 6:
            prMonName = "شهریور"
            break;

        case 7:
            prMonName = "مهر"
            break;

        case 8:
            prMonName = "آبان"
            break;
        case 9:
            prMonName = "آذر"
            break;
        case 10:
            prMonName = "دی"
            break;
        case 11:
            prMonName = "بهمن"
            break;
        case 12:
            prMonName = "اسفند"
            break;
    }

    return prMonName;

}



//function getPersianMonthName(monthNo) {
//    prMonName = "";

//    switch (parseInt(monthNo)) {
//        case 1:
//            prMonName = "فَروَردین"
//            break;
//        case 2:
//            prMonName = "اردیبِهِشت"
//            break;
//        case 3:
//            prMonName = "خُرداد"
//            break;
//        case 4:
//            prMonName = "تیر"
//            break;
//        case 5:
//            prMonName = "مُرداد"
//            break;

//        case 6:
//            prMonName = "شَهریوَر"
//            break;

//        case 7:
//            prMonName = "مِهر"
//            break;

//        case 8:
//            prMonName = "آبان"
//            break;
//        case 9:
//            prMonName = "آذَر"
//            break;
//        case 10:
//            prMonName = "دَی"
//            break;
//        case 11:
//            prMonName = "بَهمَن"
//            break;
//        case 12:
//            prMonName = "اِسفَند"
//            break;
//    }

//    return prMonName;

//}


function GetPerYearDigit(engYr) {
    var prYr = "";
    prYr = getperDayDigit(engYr.substr(0, 1)) + getperDayDigit(engYr.substr(1, 1)) + getperDayDigit(engYr.substr(2, 1)) + getperDayDigit(engYr.substr(3, 1))
    return prYr;
}


function GetPersianYear(engYr) {
    var prYr = "";
    prYr = GetPersianDay(engYr.substr(0, 1)) + GetPersianDay(engYr.substr(1, 1)) + GetPersianDay(engYr.substr(2, 1)) + GetPersianDay(engYr.substr(3, 1))
    return prYr;
}


function ConvertPerDate_String_To_Digit(dd, mm, yy) {

    var ret = "";
    ret = GetPerYearDigit(yy) + "/" + getPerMonthDigit(mm) + "/" + getperDayDigit(dd)

    return ret;
}


function EngDayNameToPerDayName(nm) {
    prMonName = "";

    switch (nm.toLowerCase()) {
        case "sun":
            prMonName = "یک شنبه"
            break;
        case "mon":
            prMonName = "دوشنبه"
            break;
        case "tue":
            prMonName = "سه شنبه"
            break;
        case "wed":
            prMonName = "چهارشنبه"
            break;
        case "thu":
            prMonName = "پنج شنبه"
            break;

        case "fri":
            prMonName = "جمعه"
            break;

        case "sat":
            prMonName = "شنبه"
            break;


    }

    return prMonName

}


function numToPerDayName(nm) {
    prMonName = "";

    switch (parseInt(nm)) {
        case 1:
            prMonName = "یک شنبه"
            break;
        case 2:
            prMonName = "دوشنبه"
            break;
        case 3:
            prMonName = "سه شنبه"
            break;
        case 4:
            prMonName = "چهارشنبه"
            break;
        case 5:
            prMonName = "پنج شنبه"
            break;

        case 6:
            prMonName = "جمعه"
            break;

        case 7:
            prMonName = "شنبه"
            break;


    }

    return prMonName

}


function numToDayName(nm) {
    prMonName = "";

    switch (parseInt(nm)) {
        case 1:
            prMonName = "Sun"
            break;
        case 2:
            prMonName = "Mon"
            break;
        case 3:
            prMonName = "Tue"
            break;
        case 4:
            prMonName = "Wed"
            break;
        case 5:
            prMonName = "Thu"
            break;

        case 6:
            prMonName = "Fri"
            break;

        case 7:
            prMonName = "Sat"
            break;


    }

    return prMonName

}

//Ended here code for persian Calendar


// number format
function number_format(number, decimals, decPoint, thousandsSep)
{
    decimals = decimals || 0;
    number = parseFloat(number);

    if(!decPoint || !thousandsSep)
    {
        decPoint = ".";
        thousandsSep = ",";
    }

    var roundedNumber = Math.round( Math.abs( number ) * ("1e" + decimals) ) + "";
    // add zeros to decimalString if number of decimals indicates it
    roundedNumber = (1 > number && -1 < number && roundedNumber.length <= decimals)
                ? Array(decimals - roundedNumber.length + 1).join("0") + roundedNumber : roundedNumber;
    var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber.slice(0);
    var checknull = parseInt(numbersString) || 0;

    // check if the value is less than one to prepend a 0
    numbersString = (checknull == 0) ? "0": numbersString;
    var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
    
    var formattedNumber = "";
    while(numbersString.length > 3)
    {
        formattedNumber = thousandsSep + numbersString.slice(-3) + formattedNumber;
        numbersString = numbersString.slice(0,-3);
    }

    return (number < 0 ? "-" : "") + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : "");
}


// extra string functionalities
String.prototype.multi_replace = function(obj)
{
    var retStr = this;
    for (var x in obj)
    retStr = retStr.replace(new RegExp(x, 'g'), obj[x]);
    return retStr;
};

String.prototype.str_pad_left = function(replacement, max)
{
    var str_pad = this.toString();
    return str_pad.length < max ? (replacement + str_pad).str_pad_left(replacement, max) : str_pad;
};

/* Date Picker*/
function generate_dp(form_name, language)
{
    if($(form_name +' .from_date').length > 0)
    {
        calendar = $.calendars.instance(language);
        $(form_name +' .from_date:not(:disabled)').each(function()
        {
            $("#"+$(this).attr("id")).datepick({
                calendar: calendar,
                minDate: 0,
                dateFormat: "dd-mm-yyyy",
                onSelect: function(selected_date)
                {
                    var js_date = $(this).val();
                    if(selected_date[0]._calendar === $.calendars.instance("fa"))
                        js_date = persianDate_to_Std(selected_date[0]._day, selected_date[0]._month,selected_date[0]._year);
                    if ($(this).closest("div").find("#" + $(this).attr("id") + "_date").length > 0)
                        $(this).closest("div").find("#" + $(this).attr("id") + "_date").val(js_date);
                    else
                    {
                        var str = "<input type=\"hidden\" data-rule-required=\"true\" data-msg-required=\"*\" id=\"" + $(this).attr('id') + "_date\" name=\"" + $(this).attr("name") + "\" value=\"" + js_date + "\" />";
                        $(this).after(str);
                        $(this).removeAttr("name");
                    }
                    if(selected_date[0] !== null)
                    {
                        var dp_name = $(this).attr("name") === undefined ? $("#"+$(this).attr("id") + "_date").attr("name") : $(this).attr("name");
                        var dp_id = $(this).attr("id");
                        var dp_id_alt = dp_id+"_date";
                        if(selected_date[0] !== undefined)
                        {
                            if($("[name='"+dp_name+"']").length > 1)
                            {
                                set_min_date = false;
                                $("[name='"+dp_name+"']").each(function()
                                {
                                    if($(this).attr("id") === dp_id || $(this).attr("id") === dp_id_alt)
                                        set_min_date = true;
                                    if(set_min_date && ($(this).attr("id") !== dp_id || $(this).attr("id") !== dp_id))
                                        $(this).datepick("option", {minDate : selected_date[0]});
                                });
                            }
                            $(form_name + ' .to_date').datepick("option", {minDate : selected_date[0]});
                            $(form_name + ' .to_date').focus();
                        }
                    }
                }
            });
        });
    }

    $(form_name +' .to_date').datepick(
    {
        minDate: 0,
        calendar: calendar,
        dateFormat: "dd-mm-yyyy",
        onSelect: function(selected_date)
            {
                if ($(this).siblings("input").attr("id") !== $(this).attr("id") + "_date")
                {
                    var str = "<input type=\"hidden\" id=\"" + $(this).attr('id') + "_date\" name=\"" + $(this).attr("name") + "\" />";
                    $(this).after(str);
                    $(this).removeAttr("name");
                }
                if(selected_date[0] !== null)
                {

                var js_date = $(this).val();
                if(selected_date[0]._calendar === $.calendars.instance("fa"))
                    js_date = persianDate_to_Std(selected_date[0]._day, selected_date[0]._month,selected_date[0]._year);
                $(this).siblings("input").val(js_date);
                    var dp_name = $(this).attr("name");
                    var dp_id = $(this).attr("id");
                    if(selected_date[0] !== undefined)
                    {
                        if($("[name='"+dp_name+"']").length > 1)
                        {
                            set_min_date = false;
                            $("[name='"+dp_name+"']").each(function()
                            {
                                if(set_min_date)
                                    $(this).datepick("option", {minDate : selected_date[0]});
                                if($(this).attr("id") === dp_id)
                                    set_min_date = true;
                            });
                        }
                        $(form_name + ' .to_date').datepick("option", {minDate : selected_date[0]});
                        $(form_name + ' .to_date').focus();
                    }
                }
            }
    });
}function display_farsi(language)
{
	if(language === "fa")
	{
		$(".from_date, .to_date, .fa_content").css({"font-family" : "WYekan"});
		$(document).find("[data-fa-date]").each(function()
		{
			if($(this).data("fa-date") !== undefined && $(this).data("fa-date") !== "")
			{
				if($(this).attr("type") !== undefined)
				{
					if ($(this).siblings("input").attr("id") !== $(this).attr("id") + "_date")
					{
						var str = "<input type=\"hidden\" id=\"" + $(this).attr('id') + "_date\" name=\"" + $(this).attr("name") + "\" value=\"" + $(this).val() + "\" />";
						$(this).after(str);
						$(this).removeAttr("name");
					}
                }
				var dt_arr = $(this).data("fa-date").split("-");
				var pdt = EngDate_to_persian_EngDigit(dt_arr[0], dt_arr[1], dt_arr[2]);
				var pdt_arr = pdt.split("/");
				if($(this).attr("type") !== undefined)
					$(this).val(pdt_arr[2].str_pad_left("0", 2)+"-"+pdt_arr[1].str_pad_left("0", 2)+"-"+pdt_arr[0]);
				else
					$(this).children("label:eq(0)").html(pdt_arr[2].str_pad_left("0", 2)+"-"+pdt_arr[1].str_pad_left("0", 2)+"-"+pdt_arr[0]);
			}
		});
	}
}
 /* End Date Picker*/