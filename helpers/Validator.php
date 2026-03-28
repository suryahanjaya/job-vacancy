<?php
/**
 * Server-Side Validator
 */

class Validator
{
    private $errors = [];
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function required($field, $label = null)
    {
        $label = $label ?? $field;
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = "$label is required.";
        }
        return $this;
    }

    public function email($field, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "$label must be a valid email address.";
        }
        return $this;
    }

    public function minLength($field, $min, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "$label must be at least $min characters.";
        }
        return $this;
    }

    public function maxLength($field, $max, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "$label must not exceed $max characters.";
        }
        return $this;
    }

    public function numeric($field, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "$label must be a number.";
        }
        return $this;
    }

    public function min($field, $min, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && is_numeric($this->data[$field]) && $this->data[$field] < $min) {
            $this->errors[$field] = "$label must be at least $min.";
        }
        return $this;
    }

    public function max($field, $max, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && is_numeric($this->data[$field]) && $this->data[$field] > $max) {
            $this->errors[$field] = "$label must not exceed $max.";
        }
        return $this;
    }

    public function in($field, $values, $label = null)
    {
        $label = $label ?? $field;
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = "$label has an invalid value.";
        }
        return $this;
    }

    public function matches($field, $matchField, $label = null, $matchLabel = null)
    {
        $label = $label ?? $field;
        $matchLabel = $matchLabel ?? $matchField;
        if (isset($this->data[$field]) && $this->data[$field] !== ($this->data[$matchField] ?? null)) {
            $this->errors[$field] = "$label must match $matchLabel.";
        }
        return $this;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function fails()
    {
        return !$this->passes();
    }

    public function errors()
    {
        return $this->errors;
    }

    public function firstError()
    {
        return reset($this->errors) ?: '';
    }
}
