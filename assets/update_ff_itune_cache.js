function updateFFItuneCache(id) {
    var baseUrl = Joomla.getOptions('system.paths').root;

    jQuery.ajax({
        url: baseUrl + '?option=com_ajax&module=ff_itune_feed&method=updateCache&format=json&id=' + id,
    });
}