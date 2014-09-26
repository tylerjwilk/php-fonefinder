<?php

#doc
#    classname:    FoneFinder
#    scope:        PUBLIC
# description:     Reverse cell carrier lookups to convert cell phone numbers to carrier names and gateways.
#                  This is very rudimentary and will only catch the major cases/carriers. If you are looking for 100% reliably you are in the wrong place.
#/doc

class FoneFinder
{

    /*
     * Constructor
     */
    function __construct() { /* nothing */ }
    
    /*
     * Find the carrier data of a given cell number
     */
    public function find($npa, $nxx, $thoublock)
    {
        $full       = $npa . $nxx . $thoublock;
        $href       = "http://www.fonefinder.net/findome.php?npa=$npa&nxx=$nxx&thoublock=$thoublock&usaquerytype=Search+by+Number";
        $contents   = file_get_contents($href);
        $carrier    = $this->getCarrier($contents);
        $gateway    = $this->getGateway($carrier);
        $result     = array
        (
            'npa'       => $npa,
            'nxx'       => $nxx,
            'thoublock' => $thoublock,
            'full'      => $full,
            'carrier'   => $carrier,
            'gateway'   => $gateway,
            'email'     => $full . '@' . $gateway,
        );
        return $result;
    }
            
    /*
     * Get the carrier from the contents of the html based on known fingerprints
     */
    function getCarrier($contents)
    {
        if (!$contents) return false;
        foreach ($this->getFingerprints() as $fingerprint => $carrier)
        {
            if (strpos($contents, $fingerprint) === FALSE) continue;
            return $carrier;
        }
        return false; // unknown carrier
    }
    
    /*
     * Get a list of fingerprints known to identify carriers
     */
    function getFingerprints()
    {
        $fingerprints = array
        (
            "<A HREF='http://fonefinder.net/verizon.php'>"  => 'verizon',
            "<A HREF='http://fonefinder.net/att.php'>"      => 'att',
            "<A HREF='http://fonefinder.net/qwest.php'>"    => 'qwest',
            "<A HREF='http://fonefinder.net/sprint.php'>"   => 'sprint',
            "<A HREF='http://fonefinder.net/tmobile.php>"   => 'tmobile',
            "<A HREF='http://fonefinder.net/alltel.php'>"   => 'alltel',
            "<A HREF='http://fonefinder.net/boostmobile.php'>"   => 'boost',
            "<A HREF='hhttp://fonefinder.net/cricket.php'>"   => 'cricket',
            "<A HREF='http://fonefinder.net/metropcs.php'>"   => 'metropcs',


        );            
        return $fingerprints;
    }
    
    /*
     * Get a gateway of a given carrier
     */
    function getGateway($carrier)
    {
        if (!$carrier) return false;
        $gateways = array
        (
            'verizon' => 'vtext.com',
            'att'     => 'txt.att.net',
            'qwest'   => 'qwestmp.com',
            'sprint'  => 'messaging.sprintpcs.com',
            'tmobile'  => 'tmomail.net',
            'alltel' => 'message.alltel.com',
            'boost' => 'myboostmobile.com',
            'cricket' => 'sms.mycricket.com',
            'metropcs' => 'mymetropcs.com',
        );
        return $gateways[$carrier];
    }

    /*
     * I was going to right a validator using regex, but
     */
    function validate($npa, $nxx, $thoublock)
    {
        return ((sizeof($npa)) == 3 && (sizeof($nxx)) == 3 && (sizeof($thoublock)) == 4) ? true : false;
    }

    /*
     *  Splits the string, assumption is that it's a 10 digit number
     */
    function splitNumber($phone)
    {
        $phone = preg_replace("~[^0-9]~", "", $phone);
        $components = array
        (
            'npa' => substr($phone,0,3),
            'nxx' => substr($phone,2,3),
            'thoublock' => substr($phone, 5, 4),
        );
        return $components;
    }
    
}

