(function ($) {

    $(function() {
        $(document).delegate('.add-product', 'click', addProduct);
        $(document).delegate('.remove-product', 'click', removeProduct);
        $(document).bind('product.added product.removed', onCartChanged);
        $(document).delegate('.product_productOptions_remove', 'click', removeTagForm);
        $collectionHolder = $('#product_productOptions');
        $collectionHolder.data('index', $collectionHolder.find('select').length);
        $addTagLink = $('#product_productOptions_add');
        $addTagLink.on('click', function(e) {
            e.preventDefault();
            addTagForm($collectionHolder, $addTagLink);
        });
        $('#popover-alert').popover('show');
    });

    function addTagForm($collectionHolder, $addTagLink) {
        var prototype = $('#product_productOptions_prototype').html();
        var index = $collectionHolder.data('index');
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        $addTagLink.closest('div').before(newForm);
    }

    function removeTagForm(e) {
        $(this).closest('.form-group').remove();
    }

    function error() {
        alert('An error has occurred');
    }

    function addProduct(event) {
        event.preventDefault();
        var element = $(event.target),
            product = element.data('product'),
            url = element.data('url'),
            data = {
                quantity: 1,
                productId: product
            };
        element.attr('disabled', true);
        $.post(url, data)
            .then(trigger('product.added'), error)
            .always(function () {
                element.attr('disabled', false);
            });
    }

    function removeProduct(event) {
        event.preventDefault();
        var element = $(event.target),
            url = element.data('url');
        element.attr('disabled', true);
        $.ajax({
                url: url,
                type: "DELETE"
            })
            .then(function () {
                element.parents('tr').remove();
                trigger('product.removed')();
            }, error);
    }

    function trigger(eventName) {
        return function () {
            $(document).trigger(eventName);
        }
    }

    function updateCart(data) {
        $('#profile_nav').replaceWith(data);
    }

    function onCartChanged() {
        var url = $('#cart_link').data('url');
        $.get(url).then(updateCart, error);
        var table = $('#cart_table');
        var exist = $('tbody tr', table).length > 0;
        table.toggleClass('hide', !exist);
        $('#cart_empty').toggleClass('hide', exist);
        $('#form_create_order').toggleClass('hide', !exist);
    }

})(jQuery);