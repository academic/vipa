<?php

namespace Ojstr\WorkflowBundle\Validation;

use Ojstr\WorkflowBundle\Validation\Violation;

/**
 * Violation list of step validations.
 *
 * @author Jeremy Barthe <j.barthe@lexik.fr>
 * @author Gilles Gauthier <g.gauthier@lexik.fr>
 */
class ViolationList implements \IteratorAggregate, \Countable, \ArrayAccess {

    /**
     * @var Violation[]
     */
    private $violations = array();

    /**
     * Converts the violation list as string.
     *
     * @return string
     */
    public function __toString() {
        $output = '';
        foreach ($this->violations as $violation) {
            $output .= $violation->getMessage() . "\n";
        }

        return $output;
    }

    /**
     * @param Violation $violation
     */
    public function add(Violation $violation) {
        $this->violations[] = $violation;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset) {
        if (!isset($this->violations[$offset])) {
            throw new \OutOfBoundsException(sprintf('The offset "%s" does not exist.', $offset));
        }

        return $this->violations[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset) {
        return isset($this->violations[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $violation) {
        if (!$violation instanceof Violation) {
            throw new \InvalidArgumentException('You must pass a valid Violation object');
        }

        $this->violations[$offset] = $violation;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset) {
        unset($this->violations[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator() {
        return new \ArrayIterator($this->violations);
    }

    /**
     * {@inheritDoc}
     */
    public function count() {
        return count($this->violations);
    }

    /**
     * Cast violations to flat array.
     *
     * @return array
     */
    public function toArray() {
        $data = array();
        foreach ($this->violations as $violation) {
            $data[] = $violation->getMessage();
        }

        return $data;
    }

}
