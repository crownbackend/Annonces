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
                required: true,
                regex: "/^(\\d*([.,](?=\\d{3}))?\\d+)+((?!\\2)[.,]\\d\\d)?$/"
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
                required: "Veuillez mettre un prix minimum de 1€.",
                regex: "Il faut un nombre"
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
            }
        }
    });
});
