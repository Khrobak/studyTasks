$(document).ready(function(){
    console.log("Script working properly");
    $('#registration').submit(function (e) {
        e.preventDefault();
        const data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'ok') {
                    alert('Вы зарегистрированы');
                    window.location.href = '/'
                } else alert(response.errors[0]);
            },
        })
    })

    $('#edit').submit(function (e) {
        e.preventDefault();
        const data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/update',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'ok') {
                    alert('Данные обновлены');
                    window.location.href = '/'
                } else alert(response.errors[0]);
            },
        })
    })

    $('#authorization').submit(function (e) {
        e.preventDefault();
        const data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '/auth',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'ok') {
                    alert('Вы авторизованы');
                    window.location.href = '/'
                } else alert(response.errors[0]);
            },
        })
    })
});