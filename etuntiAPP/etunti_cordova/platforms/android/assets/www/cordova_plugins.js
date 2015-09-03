cordova.define('cordova/plugin_list', function(require, exports, module) {
module.exports = [
    {
        "file": "plugins/cordova-plugin-whitelist/whitelist.js",
        "id": "cordova-plugin-whitelist.whitelist",
        "runs": true
    },
    {
        "file": "plugins/com.dnrps.nfc.plugin/www/NfcPlugin.js",
        "id": "com.dnrps.nfc.plugin.NfcPlugin",
        "clobbers": [
            "cordova.plugins.NfcPlugin"
        ]
    }
];
module.exports.metadata = 
// TOP OF METADATA
{
    "cordova-plugin-whitelist": "1.0.0",
    "com.dnrps.nfc.plugin": "0.0.0"
}
// BOTTOM OF METADATA
});