<?php

require_once 'vendor/autoload.php';

//use Phpml\Classification\KNearestNeighbors;
//$samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
//$labels = ['a', 'a', 'a', 'b', 'b', 'b'];
//$classifier = new KNearestNeighbors();
//$classifier->train($samples, $labels);
//$res = $classifier->predict([3, 2]);
//var_dump($res);

## Apriori Associator
//$samples = [['alpha', 'beta', 'epsilon'], ['alpha', 'beta', 'theta'], ['alpha', 'beta', 'epsilon'], ['alpha', 'beta', 'theta']];
//$labels = [];
//
//use Phpml\Association\Apriori;
//
//$associator = new Apriori($support = 0.5, $confidence = 0.5);
//$train = $associator->train($samples, $labels);
//
//$pre_1 = $associator->predict(['alpha', 'theta']);
//// return [[['beta']]]
//
//$pre_2 = $associator->predict([['alpha', 'epsilon'], ['beta', 'theta']]);
//// return [[['beta']], [['alpha']]]
//
//$ass = $associator->getRules();


##  Support Vector Classification
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;

$samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
$labels = ['a', 'a', 'a', 'b', 'b', 'b'];

$classifier = new SVC(Kernel::LINEAR, $cost = 1000);
$train = $classifier->train($samples, $labels);

$pre_1 = $classifier->predict([3, 2]);
// return 'b'

$pre_2 = $classifier->predict([[3, 2], [1, 5]]);
// return ['b', 'a']

echo '<pre>';
var_dump($pre_1);
echo '</pre>';