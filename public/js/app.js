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
            "advertisement[price]": {
                required: true
            },
            "advertisement[imageFile]": {
                required: true
            },
            "advertisement[imageFile2]": {
                required: true
            },
            "advertisement[imageFile3]": {
                required: true
            },
            "advertisement[imageFile4]": {
                required: true
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
            "advertisement[imageFile]": {
                required: "Il faut une photo"
            },
            "advertisement[imageFile2]": {
                required: "Il faut une photo"
            },
            "advertisement[imageFile3]": {
                required: "Il faut une photo"
            },
            "advertisement[imageFile4]": {
                required: "Il faut une photo"
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

    $("#number, #share").hide();
    $("#message").click(function(){
        $(this).hide();
        $("#number").show();
    });

    $("#mail").on('click', function(){
        $("#share").show();
    });
    $(".close, #ok2").on('click', function(){
        $("#share, #a-error-1, #a-error-2").hide();
        $("#ok").hide();
        console.log(test);
    });

    $("#a-error-1, #a-error-2").hide();
    $("#share_advertisement_from").on('click', function () {
        $("#a-error-1").show();
    });

    $("#share_advertisement_to").on('click', function () {
        $("#a-error-2").show();
    });

});

$(document).ready(function(){
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
