<?php


namespace Elikh;


class NamingConventionsValidator
{
    const DEFAULT_RULE_NAME = 'single';
    const DEFAULT_RULE = '/^[a-z][a-z0-9_]+[^s]$/';

    private $rules = []; //['single'=>'/^[a-z][a-z0-9_]+[^s]$/','nextName'=>'nextRule']

    private $values = []; //['val1','val2']

    private $errors = [];

    public function validate(): array
    {
        if (empty($this->rules)) {
            $this->addRule(self::DEFAULT_RULE_NAME, self::DEFAULT_RULE);
        }

        if (empty($this->values)) {
            throw new \DomainException('Nothing to validate');
        }

        foreach ($this->values as $value) {
            $this->validateValue($value);
        }

        return $this->errors;
    }

    public function addRule(string $ruleName, string $rule): void
    {
        $this->rules[$ruleName] = $rule;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function addCheckedValues(array $values): void
    {
        $this->values = array_merge($this->values,$values);
    }

    public function getCheckedValues():array
    {
        return $this->values;
    }

    public function getErrorMessage(string $glue = ' / '): string
    {
        return implode($glue,$this->errors);
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