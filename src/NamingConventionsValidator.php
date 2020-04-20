<?php


namespace App;

use PHPUnit\Framework\TestCase;


class NamingConventionsValidator extends TestCase
{
    private $rules = []; //['single'=>'/^[a-z][a-z0-9_]+[^s]$/','nextName'=>'nextRule']

    private $checkedValues = []; //['val1','val2']

    private $errors = [];

    public function validate(array $checkedValues, array $rule = ['single' => '/^[a-z][a-z0-9_]+[^s]$/']): string
    {
        $this->checkedValues = $checkedValues;
        $this->addRule($rule);

        foreach ($this->checkedValues as $checkedValue) {
            $this->validateValue($checkedValue);
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

    private function validateValue(string $checkedValue): void
    {
        foreach ($this->rules as $ruleName => $rule) {
            if (!$this->check($checkedValue, $rule)) {
                $this->addError($checkedValue, $ruleName);
            }
        }
    }

    private function check(string $checkedValue, string $rule): bool
    {
        return preg_match($rule, $checkedValue) ? true : false;
    }

    private function addError(string $checkedValue, string $ruleName): void
    {
        $this->errors[] = "Value '$checkedValue' does not match rule '$ruleName'";
    }
}