// console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
let recipe = document.querySelector('#heart_like').dataset.recipe

$.ajax({
    type: "POST",
    url: 'http://localhost:81/getLikes?',
    headers: {
        "Access-Control-Allow-Origin": "*",
    },
    data: `recipe=${recipe}`,
    success: function (rep) {
        let response = JSON.parse(rep)

        if (response.recipeLikes == 0) {
            $('#heart_like').addClass('unliked')
        } else {
            $('#heart_like').addClass('liked')
        }
    }
});

$('#heart_like').on('click', () => {

    let recipe = document.querySelector('#heart_like').dataset.recipe

    if ($('#heart_like').hasClass('liked')) {
        $("#heart_like").removeClass("liked");
        $('#heart_like').addClass("unliked");
    } else {
        $('#heart_like').addClass("liked");
        $("#heart_like").removeClass("unliked");
    }

    $.ajax({
        type: "POST",
        url: 'http://localhost:81/createLike?',
        headers: {
            "Access-Control-Allow-Origin": "*",
        },
        data: `action=like&recipe=${recipe}`,
        success: function (rep) {
            let response = JSON.parse(rep)
            $('#count_like').html(response.count_likes)

        }
    });

});