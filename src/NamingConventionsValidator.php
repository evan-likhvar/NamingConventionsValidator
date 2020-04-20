<?php


namespace App;

use PHPUnit\Framework\TestCase;


class NamingConventionsValidator extends TestCase
{
    private $rules = []; //['single'=>'/^[a-z][a-z0-9_]+[^s]$/','nextName'=>'nextRule']

    private $values = []; //['val1','val2']

    private $errors = [];

    public function validate(array $values, array $rule = ['single' => '/^[a-z][a-z0-9_]+[^s]$/']): string
    {
        $this->values = $values;
        $this->addRule($rule);

        foreach ($this->values as $value) {
            $this->validateValue($value);
        }

        return implode(PHP_EOL,$this->errors);
    }

    public function addRule(array $rule): void
    {
        $this->rules = array_merge($this->rules, $rule);
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    private function validateValue(string $value): void
    {
        foreach ($this->rules as $ruleName => $rule) {
            if (!$this->check($value, $rule)) {
                $this->addError($value, $ruleName);
            }
        }
    }

    private function check(string $value, string $rule): bool
    {
        return preg_match($rule, $value) ? true : false;
    }

    private function addError(string $value, string $ruleName): void
    {
        $this->errors[] = "Value '$value' does not match rule '$ruleName'";
    }
}