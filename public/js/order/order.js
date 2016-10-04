$(document).ready(function(){

    $('input[name="price"]').priceFormat({
        prefix: '',
    });

    // Add item
    $(".add").on("click", function(){
        itemId = $(this).parents('.item-data').data('id');
        itemQuantity = $(this).parents('.item-data').find('input').val();

        makeRequest({ id: itemId, qty: itemQuantity }, 'add');
    });

    // Update quantity
    $(".item-quantity").on("change", function(){
        itemId = $(this).parents('.item-data').data('id');
        quantity = $(this).val();
        limit = parseInt($(this).attr('max').trim());

        if (quantity < 1 || quantity > limit) {
            $(this).parent().find('.limit').fadeTo(100, 0.1).fadeTo(200, 1.0);
            $(this).val("");
        } else {
            itemPrice = $(this).parents('.item-data').find(".item-price").text();
            totalItemSum = $(this).parents('.item-data').find(".total-item-sum");
            totalItemSum.text((itemPrice * quantity).toFixed(2));
            makeRequest({ qty: quantity, id: itemId }, 'update');
        }
    });

    // Delete item
     $(".remove-item").on("click", function(){
        itemRow = $(this).parent().parent();
        itemId = itemRow.data('id');
        makeRequest({ id: itemId }, 'remove', itemRow);
        console.log($('tr').length);
        if ($('tr').length == 2) {
            $('h1.title').append(' (Empty)');
            $('.order-table').remove();
            $('.order-btn').hide();
        }
    });

    // VALIDATORS

    // Positive nums validator
    $("input[type='number']").keypress(function(e){
        return /[0-9,.]/.test(String.fromCharCode(e.keyCode));
    });

    // Limit, negativ validation
    $(".item-data input").on('focusout', function(){
        value = $(this).val().trim();
        limit = parseInt($(this).parent().find('.item-limit').text().trim());
        error = $(this).closest('.item-data').find('.error-message');

        if (value < 0 || value > limit) {
            $(this).val(0);
            error.text('Non valid quantity');
        } else {
            error.text('');
        }

    });
});

var makeRequest = function(data, type, selector) {
    $.ajax({
        url: '/order/' + type + 'Item',
        type:       'POST',
        dataType:   'json',
        async:      true,
        data:       data,
        success: function(data, status){
            qty = data['qty'].valueOf();
            sum = data['sum'].valueOf();
            $('.quantity').text(qty);
            $('.sum').text(sum);
            if (type == 'remove') { selector.remove(); }
        },
        error : function(xhr, textStatus, errorThrown) {
            console.log('Error', 'Server error');
        }
    });
};
