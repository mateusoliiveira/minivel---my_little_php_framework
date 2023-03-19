<?php

class Validator
{

    private $rules;
    private $errors;

    public function __construct($rules)
    {
        $this->rules = $rules;
        $this->errors = [];
    }

    public function validate($data)
    {
        foreach ($this->rules as $field => $rule) {
            $validations = $this->parseRule($rule);
            foreach ($validations as $validation) {
                $this->validateField($field, $data, $validation);
            }
        }
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function parseRule($rule)
    {
        $validations = [];
        preg_match_all('/\[(.*?)\]/', $rule, $matches);
        foreach ($matches[1] as $match) {
            $parts = explode('>', $match);
            if (count($parts) == 2) {
                $validations[] = ['type' => 'length', 'operator' => '>', 'value' => $parts[1]];
            } else {
                $parts = explode('<', $match);
                if (count($parts) == 2) {
                    $validations[] = ['type' => 'length', 'operator' => '<', 'value' => $parts[1]];
                } else {
                    $parts = explode('number', $match);
                    if (count($parts) == 2) {
                        $value_parts = explode('>', $parts[1]);
                        if (count($value_parts) == 2) {
                            $validations[] = ['type' => 'number', 'operator' => '>', 'value' => $value_parts[1]];
                        } else {
                            $value_parts = explode('<', $parts[1]);
                            if (count($value_parts) == 2) {
                                $validations[] = ['type' => 'number', 'operator' => '<', 'value' => $value_parts[1]];
                            } else {
                                $validations[] = ['type' => 'number', 'operator' => '', 'value' => ''];
                            }
                        }
                    } else {
                        if (strpos($match, '@') !== false) {
                            $validations[] = ['type' => 'email', 'operator' => '', 'value' => ''];
                        } else {
                            $validations[] = ['type' => '', 'operator' => '', 'value' => ''];
                        }
                    }
                }
            }
        }
        return $validations;
    }

    private function validateField($field, $data, $validation)
    {
        $value = isset($data[$field]) ? $data[$field] : '';
        switch ($validation['type']) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'is not a valid email');
                }
                break;
            case 'length':
                $length = strlen($value);
                switch ($validation['operator']) {
                    case '>':
                        if ($length <= $validation['value']) {
                            $this->addError($field, 'is too short');
                        }
                        break;
                    case '<':
                        if ($length >= $validation['value']) {
                            $this->addError($field, 'is too long');
                        }
                        break;
                }
                break;
            case 'number':
                if (!is_numeric($value)) {
                    $this->addError($field, 'is not a number');
                } else {
                    $number = floatval($value);
                    switch ($validation['operator']) {
                        case '>':
                            if ($number <= $validation['value']) {
                                $this->addError($field, 'is too small');
                            }
                            break;
                        case '<':
                            if ($number >= $validation['value']) {
                                $this->addError($field, 'is too big');
                            }
                            break;
                        default:
                            break;
                    }
                }
                break;
            default:
                break;
        }
    }

    private function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }
}
