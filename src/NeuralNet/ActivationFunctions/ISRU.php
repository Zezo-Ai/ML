<?php

namespace Rubix\ML\NeuralNet\ActivationFunctions;

use MathPHP\LinearAlgebra\Matrix;
use InvalidArgumentException;

class ISRU implements ActivationFunction
{
    /**
     * At which point the output values of the function will saturdate. i.e.
     * alpha = 2.0 means that the output will be between + or - 1 / sqrt(2.0).
     *
     * @var float
     */
    protected $alpha;

    /**
     * @param  float  $alpha
     * @throws \InvalidArgumentException
     * @return void
     */
    public function __construct(float $alpha = 1.0)
    {
        if ($alpha < 0) {
            throw new InvalidArgumentException('Alpha parameter must be a'
                . ' positive value.');
        }

        $this->alpha = $alpha;
    }

    /**
     * Return a tuple of the min and max output value for this activation
     * function.
     *
     * @return array
     */
    public function range() : array
    {
        $r = 1 / sqrt($this->alpha);

        return [-$r, $r];
    }

    /**
     * Compute the output value.
     *
     * @param  \MathPHP\LinearAlgebra\Matrix  $z
     * @return \MathPHP\LinearAlgebra\Matrix
     */
    public function compute(Matrix $z) : Matrix
    {
        return $z->map(function ($value) {
            return $value / sqrt(1 + $this->alpha * $value ** 2);
        });
    }

    /**
     * Calculate the derivative of the activation function at a given output.
     *
     * @param  \MathPHP\LinearAlgebra\Matrix  $z
     * @param  \MathPHP\LinearAlgebra\Matrix  $computed
     * @return \MathPHP\LinearAlgebra\Matrix
     */
    public function differentiate(Matrix $z, Matrix $computed) : Matrix
    {
        return $z->map(function ($output) {
            return (1 / sqrt(1 + $this->alpha * $output ** 2)) ** 3;
        });
    }
}