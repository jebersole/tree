// Есть яблоки или убрать их с дерева

$(function(){
    let height = 450 - $('#tree-grid').height();
    $('#ground-grid').css('margin-top', height + 'px')
    $(".popover-apple").popover({
        html: true,
        placement: 'bottom',
        sanitize: false,
        content: `
                    <div class="btn btn-primary fall">Упасть</div>
                    <input class="form-control percent" type="number" value="0" min="0" max="100">
                    <div class="btn btn-primary eat">Съесть %</div>
                    <div class="message"></div>
                `
    }).click(function() {
        $('#ground-grid .popover').children('.popover-content').children('.fall').attr('disabled', true);
        $('.popover-content div.btn').click(function() {
            let pop = $(this);
            let messageDiv = pop.siblings('.message');
            messageDiv.text('').removeClass('alert alert-danger');
            
            let url, data = {};
            if (pop.hasClass('eat')) {
                url = '/eat/';
                data = { percent_eaten: pop.siblings('.percent').val() };
                if (!parseInt(data.percent_eaten)) return;
            } else {
                if (pop.parents('#ground-grid').length) return;
                url = '/fall/';
            }
            
            let popId = pop.parents('.popover').attr('id');
            let appleLink = $("[aria-describedby=" + popId + "]");
            let appleId = appleLink.find('img').attr('id');
            $.ajax({
                url: url + appleId,
                data: data,
                type: 'PUT',
                success: function(res) {
                    if (res.errors) {
                        messageDiv.addClass('alert alert-danger').text(res.errors);
                        return;
                    }
                    
                    if (res.fallen) {
                        location.reload();
                        return;
                    }
                    
                    messageDiv.addClass('alert alert-success').text('Вкусно!');
                    if (res.allEaten) {
                        messageDiv.text('Больше не осталось...');
                        setTimeout(function() { 
                            $('#' + popId).remove();
                            appleLink.remove();
                        }, 2000);
                    }
                },
                error: function(res) {
                    if (res.responseJSON.errors) {
                        console.log('errors: ' + Object.values(res.responseJSON.errors).join(' '));
                        messageDiv.addClass('alert alert-danger').text(Object.values(res.responseJSON.errors).join(' '));
                    }
                }
            });
        });
    });
});
