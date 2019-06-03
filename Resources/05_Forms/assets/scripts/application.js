// application.js
// Copyright © 2019 NextStep IT Training. All rights reserved.
//
// Note: This script uses features from ECMAScript 6 a.k.a JavaScript 2015.
//

// On page-load run the script that initializes the application form handling.

$(document).ready( () => {

    // On the click of the submit button for the form perform input data validation.

    $('#temperature-conversion-form').submit( (e) => {

        // Clear all existing error messages.

        var generalErrorField = $('#general-errors')
        generalErrorField.empty()

        var celsiusErrorField = $('#celsius-errors')
        celsiusErrorField.empty()

        var fahrenheitErrorField = $('#fahrenheit-errors')
        fahrenheitErrorField.empty()

        // Check that one field is set.

        var newElement
        var generalErrors = oneFieldIsSet()

        if (generalErrors.length) {

            // The general error messages are separated from the field messages; if there are general messages
            // the field messages are not displayed, in the same form as the PHP side does.

            generalErrors.forEach(message => {

                newElement = $('<span>')
                newElement.html(`${message}<br/>`)
                generalErrorField.append(newElement)
            })

            e.preventDefault()

        } else {

            // Check that a list of numbers is in each field.

            var celsiusErrors = isNumberList('celsius')
            var fahrenheitErrors = isNumberList('fahrenheit')

            // If there are errors, display them and block form submission.

            if (generalErrors.length || celsiusErrors.length || fahrenheitErrors.length) {

                var notFirst

                if (celsiusErrors.length) {

                    notFirst = false

                    celsiusErrors.forEach(message => {

                        newElement = $('<span>')
                        newElement.html(`${notFirst ? ', ' : ''}${message}`)
                        celsiusErrorField.append(newElement)
                        notFirst = true
                    })
                }

                if (fahrenheitErrors.length) {

                    notFirst = false

                    fahrenheitErrors.forEach(message => {

                        newElement = $('<span>')
                        newElement.html(`${notFirst ? ', ' : ''}${message}`)
                        fahrenheitErrorField.append(newElement)
                        notFirst = true
                    })
                }

                e.preventDefault()
            }
        }
    })

    // One field set validation.

    function oneFieldIsSet() {

        var messages = []
        var celsiusTemperatures = $('#celsius')
        var fahrenheitTemperatures = $('#fahrenheit')

        if (celsiusTemperatures.val() !== "" && fahrenheitTemperatures.val() !== "") {

            messages.push('One of the Celsius or Fahrenheit fields must have one or more numeric values, but not both.')
        }

        return messages
    }


    // Numeric list validation.

    function isNumberList(fieldId) {

        var messages = []
        var field = $(`#${fieldId}`)

        if (field.length && field.val() !== '') {

            var values = field.val().split(/, *|,/)

            values.forEach( value => {

                // isNaN works differently than is_numeric in PHP; an empty string returns true.

                if (value === '' || isNaN(value)) {

                    messages.push(`'${value}' is not a number`)
                }
            })
        }

        return messages
    }
})
