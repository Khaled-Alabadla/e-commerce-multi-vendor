(function ($) {
    $(".item-quantity").on("change", function (e) {
        $.ajax({
            url: "/cart/" + $(this).data("id"),
            method: "put",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "quantity": $(this).val(),
                // "_token": "{{ csrf_token() }}",
            },
        });
    });

    $(".item-delete").on("click", function (e) {
        let id = $(this).data("id")
        $.ajax({
            url: "/cart/" + id ,
            method: "delete",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: response => {
                $(`#${id}`).remove();
            }
        });
    });
})(jQuery);

