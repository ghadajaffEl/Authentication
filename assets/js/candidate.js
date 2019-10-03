
$(document).ready(function () {
    $('.btn-wlc').click(function () {

        $.get({
                url: 'candidate'
            })
            .done(function (response) {

                $('#profile').show();
                $('#name').html(response.name);
                $('#email').html('email: '+response.email);

            })
    })

    $("#form-user").submit(function (e) {
        e.preventDefault();
        data=$(this).serialize();
        $.post({
            url:'/addCandidate',
            data:data
        })
            .done(function (response) {
                $(".candidateList").prepend(response.newUser);
                $("#form-user").trigger("reset");



            })
            .fail(function () {
                $("#form-user").trigger("reset");

            })
    })


})