$(document).ready(function() {
    var $ipMode = $(':radio[@name=ipMode]');
    if ($ipMode.length) {
        var onChange = function() {
            var sel = $(this).val();
            var $manualIpConfig = $('#manualIpConfig');
            if (sel == 'dhcp') {
                $manualIpConfig.hide();
            } else {
                $manualIpConfig.show();
            }
        };
        onChange.apply($ipMode.filter(':checked')[0]);
        $ipMode.change(onChange);
    }

    var $pingResult = $('#pingResult');
    if ($pingResult.length) {
        pingStoreServer();
    }
});

function pingStoreServer() {
    var $pingResult = $('#pingResult');
    $pingResult.attr('class', 'info').find('.message').html('Testing connection to DICOM server&hellip;');
    $.get('/ping', function(pingSuccessful) {
        $pingResult.removeClass('info');
        if (pingSuccessful) {
            $pingResult.addClass('success').find('.message').html('Connection to DICOM store server successful.');
        } else {
            $pingResult.addClass('error').find('.message').html('Connection to DICOM store server <em>failed</em>.');
        }
    });
}