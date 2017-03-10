$(document).ready(function() {
    $('.nette-jstree').each(function() {

        $(this).jstree({
            'core' : {
                'check_callback': true,
                'themes': {
                    'name': 'proton'
                },
                'data' : {
                    'url' : $(this).data('load-url'),
                    'data' : function (node) { return { 'id' : node.id }; }
                }
            }
        });

        $(this).on('select_node.jstree', function (e, data) {
            var url = $(this).data('execute-url') + '&id=' + data.node.id;
            $.nette.ajax({ url: url });
        });
    });
});