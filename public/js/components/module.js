/**
*-------------------------------------------------------------------------------------------------
* Function responsible for disabling & enabling button
* on clicking on checkbox.
*-------------------------------------------------------------------------------------------------
*/
function activateDisableButton(currentAttr, disabledButton) {
    let countChecked = $(currentAttr).filter(':checked').length;
    if (countChecked > 0) {
        $(disabledButton).attr('disabled', false);
    } else {
        $(disabledButton).attr('disabled', true);
    }
}

/**  Function for checking a NULL and Underfine data. */
function checkForNull(compare) {
    if (compare === null || compare === undefined || compare === '') {
        return '-';
    } else {
        return compare;
    }
}

/**
 * Function for checking a NULL and Undefined data.
 * Replacing with another data if null or undefined detected.
*/
function checkForData(compare, alternate) {
    if (compare === null || compare === undefined) {
        return alternate;
    } else {
        return compare;
    }
}

/**  Function for checking a NULL and Undefined data.
 *  Replacing with another data if null or undefined detected.
 */
function returnData(compare, data, alternate) {
    if (compare === null || compare === undefined || compare === '') {
        return alternate;
    } else {
        return data;
    }
}

/**
 * Return data if not NULL, undefined, or empty.
*/
function notNullShowData(compare, key, alternate) {
    if (compare === null || compare === undefined || compare === '') {
        return alternate;
    } else {
        return compare[key];
    }
}

function addToDate(date, day) {
    var created_date = new Date(date);
    return created_date.setDate(created_date.getDate() + parseInt(day));
}

function diffInDays(date_1, date_2) {
    const date1 = new Date(date_1);
    const date2 = new Date(date_2);
    const diffTime = Math.abs(date2 - date1);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

/**
*-------------------------------------------------------------------------------------------------
* Image Upload preview Function
*-------------------------------------------------------------------------------------------------
*/
function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Reusable function for geting Form ID of
 * a particular field on clicking a button in a form.
*/
function getFormID(thisClick) {
    return $('#' + $(thisClick).parents('form').attr('id'));
}

/**
 * Scroll to a particular element position defined
 */
function scrollToPosition(container, scrollTo) {
    container.animate({
        'scrollTop': scrollTo.offset().top
            - container.offset().top
            + container.scrollTop()
    }, 200);
}

function alternateScrollToPosition(scrollTo) {
    $([document.documentElement, document.body]).animate({
        scrollTop: scrollTo.offset().top
    }, 2000);
}

/**
 * Disabled selected Option(s) using ID attribute.
 */
function disableSelectedOption(optionValue, attr) {
    // Enabled all disabled options
    $(`#${attr}`).children("option:selected").siblings().removeAttr('disabled');
    document.querySelectorAll(`#${attr}`).forEach(function (select) {
        Array.from(select.options).forEach(function (option) {
            if (!in_array(option.value, optionValue) && !option.selected) {
                // Disabled selected option(s)
                option.setAttribute('disabled', 'disabled');
                option.style.setProperty('color', '#e9e9e9')
            } else {
                option.style.setProperty('color', 'black')
            }
        });
    });
}

/**
* Disabled selected Option(s)
*/
function disableSelectedOptionWithAttr(optionValue, attr) {
    // Enabled all disabled options
    $(attr).children("option:selected").siblings().removeAttr('disabled');
    // let totalSelect = $(attr).length;
    document.querySelectorAll(attr).forEach(function (select, index) {
        Array.from(select.options).forEach(function (option) {
            if (!in_array(option.value, optionValue) && !option.selected) {
                // Disabled selected option(s)
                option.setAttribute('disabled', 'disabled');
                option.style.setProperty('color', '#e9e9e9');
            } else {
                option.style.setProperty('color', 'black');
            }
        });
    });
}

/**
 * Function responsible for EXPORTing all DataTable data into EXCEL file.
*/
function newexportation(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
//end of the newexportation


/**
*-------------------------------------------------------------------------------------------------
* Reusable Function for validating form field
* by looping through the array based on predefined required on the field.
*-------------------------------------------------------------------------------------------------
*/
function formValidation(form) {
    // var curInputs = $(form).find(".d-none").removeAttr,
    var curInputs = $(form).find("input[required], textarea[required], select[required]"),
        isValid = true;

    // This remove validation errors and styles place on form input, select and textarea.
    $(document).find('input[required], textarea[required], select[required]').removeClass("border-error");
    $(document).find('span.text-red').remove();

    // This check if input are valid or not and placed error if not valid.
    for (var i = 0; i < curInputs.length; i++) {
        if (!curInputs[i].validity.valid && !$(curInputs[i]).parents('.row').hasClass('d-none')
            && !$(curInputs[i]).parents('.section').hasClass('d-none') && !$(curInputs[i]).parents('.rowColumn').hasClass('d-none')
            && !$(curInputs[i]).hasClass('exemptUpload')) {
            isValid = false;

            console.log($(curInputs[i]));
            if ($(curInputs[i]).hasClass('select2-hidden-accessible')) {
                console.log('in.............................');
                $(curInputs[i]).addClass('border-error').next('.select2-container').after(`<span class="text-red text-danger" id="error_${i}">Field is required.</span>`);
            } else if ($(curInputs[i]).hasClass('select2')) {
                $(curInputs[i]).addClass('border-error').next('.note-editable').after(`<span class="text-red text-danger" id="error_${i}">Field is required.</span>`);
            } else if ($(curInputs[i]).parents().hasClass('input-group')) {
                $(curInputs[i]).addClass('border-error').parents('.input-group').after(`<span class="text-red text-danger" id="error_${i}">Field is required.</span>`);
            } else {
                $(curInputs[i]).addClass('border-error').after(`<span class="text-red text-danger" id="error_${i}">Field is required.</span>`);
            }
        }
    }

    return isValid;
}

/**
 * Truncate word to specified length
 */
function truncateWord(title, length) {
    return jQuery.trim(title).substring(0, length).trim(this) + "...";
}

/**
 * Getting sum of numbers
 */
function sum(array) {
    return array.reduce(function (a, b) {
        return a + b;
    }, 0);
}


/**
 * numbers into 1st 2nd 3rd
 */
function ordinalSuffix(i) {
    var j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) {
        return i + "st";
    }
    if (j == 2 && k != 12) {
        return i + "nd";
    }
    if (j == 3 && k != 13) {
        return i + "rd";
    }
    return i + "th";
}

/**
 * Validate file input field
*/
function validateFileField(thisField) {
    // This remove validation errors and styles place on form input, select and textarea.
    $(document).find('input[required]').removeClass("border-error");
    $(document).find('span.text-red').remove();

    var lg = thisField[0].files.length; // get length
    var items = thisField[0].files;
    var fileSize = 0;
    var fileExtension = '';

    if (lg > 0) {
        for (var i = 0; i < lg; i++) {
            fileSize = fileSize + items[i].size; // get file size
            let extension = '.' + items[i].name.split('.').pop();
            fileExtension = $.inArray(extension, $(this).attr('accept').split(',')) !== -1 ? '' : extension;
        }
        if (fileSize > 2097152) {
            $(this).addClass('border-error').after('<span class="text-red">File size must not be more than 2 MB.</span>');
            $(this).val('');
        } else if (fileExtension !== '') {
            $(this).addClass('border-error').after(`
                <span class="text-red"><b>${fileExtension.replace('.', '')}
                    </b> file extension not allowed. You can only upload <b>${$(this).attr('accept').replace(/[.\s]/g, ' ')}</b>.
                </span>`);
            $(this).val('');
        }
    }
}

/**
 * Check if value is in array
 * @param {*} value
 * @param {*} arr
 * @returns
 */
function in_array(value, arr) {
    if ($.inArray(value, arr) === -1) {
        return true;
    }
    return false;
}


// var a = [
//     '', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ',
//     'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ',
//     'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '
// ];
// var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

/**
 * Convert number to words
*/
function toWords(num) {
    var digit = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var elevenSeries = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    var countingByTens = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var shortScale = ['', 'thousand', 'million', 'billion', 'trillion'];

    let number = num.toString();
    number = number.replace(/[\, ]/g, '');
    if (number != parseFloat(number))
        return 'not a number'; var x = number.indexOf('.');
    if (x == -1) x = number.length; if (x > 15)
        return 'too big'; var n = number.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += elevenSeries[Number(n[i + 1])] + ' '; i++; sk = 1;
            } else if (n[i] != 0) {
                str += countingByTens[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += digit[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred '; sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += shortScale[(x - i - 1) / 3] + ' '; sk = 0;
        }
    }
    if (x != number.length) {
        var y = number.length;
        str += 'point ';
        for (var i = x + 1; i < y; i++) str += digit[n[i]] + ' ';
    }
    str = str.replace(/\number+/g, ' ');
    return str.trim();
}

/**
 * Reload ajax datatable
 */
function reloadAjaxDataTable(table) {
    if (table !== undefined && table !== null) {
        table.ajax.reload();
    }
}

/**
 * Convert first letter to UpperCase
 */
function ucFirst(word) {
    if (word) {
        return word.replace(/\b[a-z]/g, function (text) {
            return text.toUpperCase();
        });
    }
    return '';
}

function allowOnlyNumeric(thisField) {
    if (/\D/g.test(thisField.val())) {
        // Filter non-digits from input value.
        let thisValue = thisField.val().replace(/\D/g, '');
        thisField.val(thisValue);
    }
}

/**
 * Automeric variable with currency symbol
 */
// const autoNumericWithSymbol = {
//     currencySymbol : '₦ ',
//     decimalCharacter : '.',
//     unformatOnSubmit: true,
//     modifyValueOnWheel: false,
//     minimumValue: 0,
//     decimalPlaces: 2,
//     decimalPlacesRawValue: 0,
// };

/**
 * Automeric variable without currency symbol
 */
// const autoNumeric = {
//     currencySymbol : '',
//     decimalCharacter : '.',
//     unformatOnSubmit: true,
//     modifyValueOnWheel: false,
//     minimumValue: 0,
//     decimalPlaces: 2,
//     decimalPlacesRawValue: 0,
// };

// /**
//  * Automeric variable without currency symbol
//  */
// const autoNumericWithoutSymbol = {
//     currencySymbol : '',
//     decimalCharacter : '.',
//     unformatOnSubmit: true,
//     modifyValueOnWheel: false,
//     minimumValue: 0,
//     decimalPlaces: 2,
//     decimalPlacesRawValue: 0,
// };

// Replicate payment_term section
function replicateForm(section, newSection, selector, buttonSection, removeButton) {
    //cloning the fieldset elements section, added and removed classes
    let sectionCloned = $(`.${section}`).last().clone().addClass(`${newSection}`).removeClass(`${section}`).appendTo(`${selector}:eq(-1)`);
    sectionCloned.find('input, select').val('');
    sectionCloned.find(`.${buttonSection}`).empty().append(removeButton);

    return sectionCloned;
}


/*
----------------------------------------------------------------------------------------
| Reusable function for commodities
----------------------------------------------------------------------------------------
*/

/**
 *  Reassured action
 */
function reassuredAction(url, text, modal, table, table2) {
    swal.fire({
        type: 'warning',
        title: "Warning",
        html: text,
        showCancelButton: true,
        confirmButtonText: "Yes, I am sure",
        cancelButtonColor: '#d33',
        confirmButtonColor: '#295339',
    }).then((result) => {
        if (result.value == true) {
            $('#spinner').show();
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $('#spinner').hide();
                    if (data.status === "success") {
                        new swal(
                            "Success",
                            data.message,
                            "success"
                        ).then(function () {
                            $(modal).modal('hide');
                            reloadAjaxDataTable(table);
                            reloadAjaxDataTable(table2);
                        });
                    }

                    if (data.status === "error") {
                        new swal(
                            "Error",
                            data.message,
                            "error"
                        );
                    }
                },
                error: function (xhr) {
                    $('#spinner').hide();
                    if (xhr.status == 422) {
                        // console.log(xhr.responseJSON);
                        $.each(xhr.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="form-text" style="color: red;">' + error[0] + '</span>'));
                        });
                        scrollToPosition($(modal), $('span.form-text').first().parents('.form-group'));
                    } else {
                        new swal(
                            ucFirst(xhr.responseJSON.status),
                            xhr.responseJSON.message,
                            xhr.responseJSON.status
                        );
                    }
                }
            });
        }
    });
}

/**
 * Submit ajax form and return with a promise
 * @param obj
 */
function processSerializeData(
    { url, dataForm, modal, tables = [], redirect = '', currentPageRedirect = false, method = "POST", popupError = false } = {}
) {
    return new Promise((resolve, reject) => {
        $(document).find('span.form-text').remove();
        $('#spinner').show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token here
            },
            url: url,
            method: method,
            data: dataForm,
            dataType: 'json',
            complete: function (data) {
                $('#spinner').hide();
            },
            success: function (data) {
                // $('#spinner').hide();

                // The popError function can only be used if you want to return
                // a message back to the same SweetAlert that submitted the form
                if (popupError && data.status == 'error') {
                    Swal.hideLoading();
                    Swal.showValidationMessage(data.message);
                    $('#swal2-validation-message').css({
                        display: 'block',
                        'text-align': 'center',
                        'font-size': '15px'
                    });
                } else {
                    Swal.fire(
                        ucFirst(!("title" in data) ? data.status : data.title),
                        data.message,
                        data.status
                    ).then(function () {
                        if (data.status == 'success') {
                            !modal ? '' : $(modal).modal('hide');

                            if (tables.length > 0) {
                                $.each(tables, function (index, table) {
                                    reloadAjaxDataTable(table);
                                });
                            }

                            if (!currentPageRedirect) {
                                if (redirect !== '') {
                                    var win = window.open(redirect, '_blank');
                                    if (win) {
                                        //Browser has allowed it to be opened
                                        win.focus();
                                    } else {
                                        //Browser has blocked it
                                        alert('Please allow popups for this website');
                                    }
                                }
                            } else {
                                redirect !== '' ? window.location.href = redirect : '';
                            }

                            resolve(data);
                        }
                    });
                }
            },
            error: function (xhr) {
                // $('#spinner').hide();
                reject(xhr);
                if (popupError) {
                    Swal.hideLoading();
                    Swal.showValidationMessage(xhr.responseJSON.message,);
                    $('#swal2-validation-message').css({
                        display: 'block',
                        'text-align': 'center',
                        'font-size': '15px'
                    });
                } else {
                    if (xhr.status == 422) {
                        $.each(xhr.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="form-text" style="color: red;">' + error[0] + '</span>'));
                        });
                        scrollToPosition($(modal), $('span.form-text').first().parents('.form-group'));
                    } else {
                        Swal.fire(
                            ucFirst(!("title" in xhr.responseJSON) ? xhr.responseJSON.status : xhr.responseJSON.title),
                            xhr.responseJSON.message,
                            'error'
                        );
                    }
                }
            }
        });
    });
}



export {
    activateDisableButton,
    checkForNull,
    checkForData,
    notNullShowData,
    getFormID,
    readURL,
    newexportation,
    formValidation,
    truncateWord,
    returnData,
    ordinalSuffix,
    sum,
    // autoNumeric,
    // autoNumericWithSymbol,
    // autoNumericWithoutSymbol,
    validateFileField,
    toWords,
    ucFirst,
    allowOnlyNumeric,
    replicateForm,
    reassuredAction,
    scrollToPosition,
    alternateScrollToPosition,
    in_array,
    disableSelectedOption,
    reloadAjaxDataTable,
    disableSelectedOptionWithAttr,
    addToDate,
    diffInDays,
    processSerializeData
}
