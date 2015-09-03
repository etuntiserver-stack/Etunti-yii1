cordova.define("com.dnrps.nfc.plugin.NfcPlugin", function(require, exports, module) { var exec = require('cordova/exec');

exports.coolMethod = function(arg0, success, error) {
    exec(success, error, "NfcPlugin", "coolMethod", [arg0]);
};

});
