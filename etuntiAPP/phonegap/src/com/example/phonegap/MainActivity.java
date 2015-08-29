package com.example.phonegap;

//import android.support.v7.app.ActionBarActivity;
//import android.support.v7.app.ActionBar;
import android.support.v4.app.Fragment;
import android.telephony.TelephonyManager;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.os.Build;
import android.location.Location;

import org.apache.cordova.DroidGap;




public class MainActivity extends DroidGap {
	private static String IMEI;
	private static String my_location;
	private String nfc;
    @Override
	public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        
        MyLocationListener.SetUpLocationListener(this);
        TelephonyManager telephonyManager = (TelephonyManager)getSystemService(Context.TELEPHONY_SERVICE);
        IMEI  = telephonyManager.getDeviceId();
        //Phno =  telephonyManager.getPhoneType();
        

        
        Button getAnswerButton = (Button) findViewById(R.id.button1);
        getAnswerButton.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v) {
            	zaxod();     	
            }
        });
        /*
        if(MyLocationListener.imHere != null){
        	 my_location =  MyLocationListener.imHere.getLatitude()+"/"+MyLocationListener.imHere.getLongitude();
        	 super.loadUrl("file:///android_asset/www/index.html?imei="+IMEI+"&location="+my_location+"&nfc="+nfc);
            } else {
        	 my_location = "GPS disabled";
        	 super.loadUrl("file:///android_asset/www/gpsdisabled.html");
            }
        */
        

    }
    public void zaxod(){
    if(MyLocationListener.imHere != null){
      	 my_location =  MyLocationListener.imHere.getLatitude()+"/"+MyLocationListener.imHere.getLongitude();
      	 super.loadUrl("file:///android_asset/www/index.html?imei="+IMEI+"&location="+my_location+"&nfc="+nfc);
          } else {
      	 my_location = "GPS disabled";
      	 super.loadUrl("file:///android_asset/www/gpsdisabled.html");
          }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        /*
        if (id == R.id.action_settings) {
            return true;
        }
        */
        return super.onOptionsItemSelected(item);
    }

    /**
     * A placeholder fragment containing a simple view.
     */
    public static class PlaceholderFragment extends Fragment {

        public PlaceholderFragment() {
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                Bundle savedInstanceState) {
            View rootView = inflater.inflate(R.layout.fragment_main, container, false);
            return rootView;
        }
    }

}
