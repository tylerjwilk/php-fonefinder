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
        );
        return $gateways[$carrier];
    }
    
}

