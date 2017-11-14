# MLPClassifier

A multilayer perceptron (MLP) is a feedforward artificial neural network model that maps sets of input data onto a set of appropriate outputs.

## Constructor Parameters

* $inputLayerFeatures (int) - the number of input layer features
* $hiddenLayers (array) - array with the hidden layers configuration, each value represent number of neurons in each layers
* $classes (array) - array with the different training set classes (array keys are ignored)
* $iterations (int) - number of training iterations
* $theta (int) - network theta parameter
* $activationFunction (ActivationFunction) - neuron activation function

```
use Phpml\Classification\MLPClassifier;
$mlp = new MLPClassifier(4, [2], ['a', 'b', 'c']);

// 4 nodes in input layer, 2 nodes in first hidden layer and 3 possible labels.

```

## Train

To train a MLP simply provide train samples and labels (as array). Example:


```
$mlp->train(
    $samples = [[1, 0, 0, 0], [0, 1, 1, 0], [1, 1, 1, 1], [0, 0, 0, 0]],
    $targets = ['a', 'a', 'b', 'c']
);
```

Use partialTrain method to train in batches. Example:

```
$mlp->partialTrain(
    $samples = [[1, 0, 0, 0], [0, 1, 1, 0]],
    $targets = ['a', 'a']
);
$mlp->partialTrain(
    $samples = [[1, 1, 1, 1], [0, 0, 0, 0]],
    $targets = ['b', 'c']
);

```

## Predict

To predict sample label use predict method. You can provide one sample or array of samples:

```
$mlp->predict([[1, 1, 1, 1], [0, 0, 0, 0]]);
// return ['b', 'c'];

```

## Activation Functions

* BinaryStep
* Gaussian
* HyperbolicTangent
* Sigmoid (default)
