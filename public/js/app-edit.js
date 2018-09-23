$(document).ready(function() {
    $('#advertisement_title').on('click',function() {
        $('#res-title').html('<div class="alert alert-primary" role="alert">\n' +
            '  Si le titre de votre annonce n\'est pas claire votre annonce sera réfuser ! \n' +
            '</div>');
    });
    $('#advertisement_description').on('click',function() {
        $('#res-description').html('<div class="alert alert-primary" role="alert">\n' +
            '  Soyer très explicite dans votre description pour éviter tout confusion des client qui souhaite acheter !\n' +
            '</div>');
    });
    $('#advertisement_price').on('click',function() {
        $('#res-price').html('<div class="alert alert-primary" role="alert">\n' +
            ' Le prix doit contenir que des chiffres ni virgule, ni point ! \n' +
            '</div>');
    });
    $('#advertisement_address').on('click',function() {
        $('#res-address').html('<div class="alert alert-primary" role="alert">\n' +
            ' Indiquer une adresse valide en France \n' +
            '</div>');
    });

    $("#form").validate({
        rules: {
            "advertisement[title]":{
                required: true,
                minlength: 3,
                maxlength: 100
            },
            "advertisement[description]": {
                required: true,
                minlength: 50,
            },
            "advertisement[address]": {
                required: true
            }
        },
        messages: {
            "advertisement[title]": {
                required: "Veuillez donner un titre à votre annonce.",
                minlength: "Il faut au minmum 3 caractère"
            },
            "advertisement[description]": {
                required: "Veuillez rédiger un texte d'annonce.",
                minlength: "Il faut au minimum 50 caractère"
            },
            "advertisement[price]": {
                required: "Veuillez mettre un prix minimum de 1€."
            },
            "advertisement[address]": {
                required: "Il faut une adresse valide"
            }
        }
    });

    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });
    $( "#form" ).validate({
        rules: {
            field: {
                required: true,
                number: true
            }
        }
    });
//advertisement_city

    $('#advertisement_address').geocomplete();
    $.fn.geocomplete('#advertisement_address');

});

function countChar(val) {
    var len = val.value.length;
    if (len >= 3500) {
        val.value = val.value.substring(0, 3500);
    } else {
        $('#res-limit').text(3500 - len);
    }
};


