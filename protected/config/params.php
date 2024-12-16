<?php

return array(
    'rheaUrl' => '#',
    'adminEmail' => 'abhi@sabiainc.com',
    'langArray' => array("hind", "es", "zh-cn"),
    'userScreen' => array('user', 'manager'),
    'authScreen' => array('dash', 'admin', 'rawmix', 'dash2', 'dash3', 'tagsettings', 'taggroup', 'monitor', 'tagqueued', 'tag','truckinfo','rfid','rfidcalmap','wclrfidlogmessages'),
    'screen_resolution' => 'wide',
    'language' => "es",
    'uicolor' => 'default',
    'bodyClass' => '',
    'writeCalib' => 1,
    'bullseye' => '12345',
    'dbName' => 'sabia_helios_v1_m2_0',
    'allFeeders' => ';',
    'features' => array('ramix' => 0, 'auto_tagging' => 0, 'import_export' => 1),
    'productProfileName' => 'soros',
    'modulesVisible' => array(
        'helios1' => array('analysis' => true,
            'tags' => true,
            'tons' => true,
            'lab_link' => true,
            'rawmix' => true,
            'feedrate' => true,
            'soros' => false,
        ),
        'soros' => array('analysis' => true,
            'tags' => true,
            'tons' => true,
            'lab_link' => false,
            'rawmix' => false,
            'feedrate' => false,
            'soros' => true,
        )
    )
);
