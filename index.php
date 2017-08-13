<?php

include 'RedGoatDump.php';

class Test {
   protected $test;
   public static $test1;
   private $test2;

   public function __construct($test)
   {
	$this->test = $test;
    $this->test2 = 222;
   }

   private static function myPrivateMethod() :string
   {
     echo "Hellluu!";
   }
}

$test = [
    'key' => 1,
    3,
    'three',
    'string',
    true,
    new Test('test'),
    [
        [
            'item' => 'value',
            [
                [
                    "obj" => (object)[
                        "param" => "value"
                    ]
                ]
            ]
        ]
    ],
    (object) [
        1 => 3
    ],
     (object) [
        'param' => 4
    ],
    (object)[]
];

$rg = new RedGoatDump;
$rg->dump(null);
$rg->dump(1);
$rg->dump('leo');
$rg->dump([]);
$rg->dump(["leo", "diana", ["tibi",  "daza", "luca", [ "aikeon", "bismarck"]]]);
$rg->dump([ 
            [
              1, 2, 3, 
                [ 
                  4, 5, 
                    [ 
                      6, 7, 8 
                    ] 
                ] 
            ],
            9, 10
          ]);
$rg->dump($test);

// echo "<pre>";
// var_dump($test);
// echo "</pre>";

