cordova.define('cordova/plugin_list', function(require, exports, module) {
module.exports = [
    {
        "file": "plugins/com.dnrps.nfc.plugin/www/NfcPlugin.js",
        "id": "com.dnrps.nfc.plugin.NfcPlugin",
        "clobbers": [
            "cordova.plugins.NfcPlugin"
        ]
    },
    {
        "file": "plugins/cordova-plugin-device/www/device.js",
        "id": "cordova-plugin-device.device",
        "clobbers": [
            "device"
        ]
    }
];
module.exports.metadata = 
// TOP OF METADATA
{
    "cordova-plugin-whitelist": "1.0.0",
    "com.dnrps.nfc.plugin": "0.0.0",
    "cordova-plugin-device": "1.0.2-dev"
}
// BOTTOM OF METADATA
});