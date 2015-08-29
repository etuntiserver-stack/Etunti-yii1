package sky.phonegap.plugins;


import org.json.JSONArray;

import android.content.Context;
import android.telephony.TelephonyManager;
import android.util.Log;

import org.apache.cordova.*;

public class ImeiPlugin extends CordovaPlugin {

    @Override
    public boolean execute(String action, JSONArray args, CallbackContext callbackContext) {
        String result = "";        
        boolean returnState  = false;
        if (action.equals("get")) {
            
        	TelephonyManager telephonyManager = (TelephonyManager)this.cordova.getActivity().getSystemService(Context.TELEPHONY_SERVICE);
            result = telephonyManager.getDeviceId();
            Log.i("!!!!!!!!!!!!!!!!!!!!!!getDeviceId !!!!!!!!!!!!!!!!!: ", result);
            if (result==null) {
            	//callback with error msg;
            	callbackContext.error("can not retrieve IMEI number of device.");
            }
            else{
            	//callback with device id;
                  callbackContext.success(result);
            }            

            returnState = true;
        }
        else {
            returnState = false;            
        }
        return  returnState;
    } 
}

