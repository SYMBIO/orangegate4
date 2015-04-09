$(function() {
    $('ul.orangegate_translationsLocales').on('click', 'a', function(evt) {
        evt.preventDefault();
        var target = $(this).attr('data-target');
        $('li:has(a[data-target="' + target + '"]), div' + target, 'div.orangegate_translations').addClass('active')
            .siblings().removeClass('active');
    });

    $('div.orangegate_translationsLocalesSelector').on('change', 'input', function(evt) {
        var $tabs = $('ul.orangegate_translationsLocales');

        $('div.orangegate_translationsLocalesSelector').find('input').each(function() {
            $tabs.find('li:has(a[data-target=".orangegate_translationsFields-' + this.value + '"])').toggle(this.checked);
        });

        $('ul.orangegate_translationsLocales li:visible:first').find('a').trigger('click');
    }).trigger('change');
});
