blueimp.Gallery.prototype.textFactory = function (obj, callback) {

    var $element = $('<iframe/>')
            .addClass('iframe-content')
            .attr('src', obj.href);

//
//    callback({
//        type: 'load',
//        target: $element[0]
//    });
    
    this.setTimeout(callback, [{
                type: 'load',
                target: $element[0]
    }]);

//    $.get(obj.href)
//            .done(function (result) {
//                $element.html(result);
//                callback({
//                    type: 'load',
//                    target: $element[0]
//                });
//            })
//            .fail(function () {
//                callback({
//                    type: 'error',
//                    target: $element[0]
//                });
//            });
    return $element[0];
};

//<a type="text/html" data-gallery