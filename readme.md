# RedDump - PHP improved var_dump function #

~~~~
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
~~~~

##<OUTPUT>##
~~~~
NULL

int(1)

"leo" str(3)

array(size=0) 
  [0] => {empty}
array(size=3) 
  [0] => "leo" str(3)
  [1] => "diana" str(5)
  [2] => array(size=4) 
    [0] => "tibi" str(4)
    [1] => "daza" str(4)
    [2] => "luca" str(4)
    [3] => array(size=2) 
      [0] => "aikeon" str(6)
      [1] => "bismarck" str(8)

array(size=3) 
  [0] => array(size=4) 
    [0] => int(1)
    [1] => int(2)
    [2] => int(3)
    [3] => array(size=3) 
      [0] => int(4)
      [1] => int(5)
      [2] => array(size=3) 
        [0] => int(6)
        [1] => int(7)
        [2] => int(8)
  [1] => int(9)
  [2] => int(10)

array(size=10) 
  ["key"] => int(1)
  [0] => int(3)
  [1] => "three" str(5)
  [2] => "string" str(6)
  [3] => bool(true)
  [4] => object(class::Test) (1) 
    "test":<default protected> => "test" str(4)
    "test1":<default public static>
    "test2":<default private> => int(222)
    @Test::__construct() <public>
    @Test::myPrivateMethod() <private static>
  [5] => array(size=1) 
    [0] => array(size=2) 
      ["item"] => "value" str(5)
      [0] => array(size=1) 
        [0] => array(size=1) 
          ["obj"] => object(class::stdClass) (1) 
            ["param"] => "value" str(5)
  [6] => object(class::stdClass) (1) 
    [1] => int(3)
  [7] => object(class::stdClass) (1) 
    ["param"] => int(4)
  [8] => object(class::stdClass) (1) 
~~~~