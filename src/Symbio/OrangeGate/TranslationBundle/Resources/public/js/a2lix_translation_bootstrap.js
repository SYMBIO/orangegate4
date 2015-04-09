$(function() {
    $('ul.orangegate_translationsLocales').on('click', 'a', function(evt) {
        evt.preventDefault();
        $(this).tab('show');
    });

    $('div.orangegate_translationsLocalesSelector').on('change', 'input', function(evt) {
        var $tabs = $('ul.orangegate_translationsLocales');

        $('div.orangegate_translationsLocalesSelector').find('input').each(function() {
            $tabs.find('li:has(a[data-target=".orangegate_translationsFields-' + this.value + '"])').toggle(this.checked);
        });

        $('ul.orangegate_translationsLocales li:visible:first').find('a').tab('show');
    }).trigger('change');
});
