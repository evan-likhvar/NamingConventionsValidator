<?php

namespace Elikh;

use PHPUnit\Framework\TestCase;

abstract class NamingConventionsValidator extends TestCase
{
    private $errors = [];

    /**
     * The method returns a list of names to check. ['name1','name2',..]
     * @return array
     */
    abstract public function getNames(): array;

    /**
     * The method returns a list of names to skip checking ['name1','name2',..]
     * @return array
     */
    abstract public function getNamesToSkip(): array;

    /**
     * The method returns an array of validation rules
     * like ['single'=>'/^[a-z][a-z0-9_]+[^s]$/','nextName'=>'next PCRE expression']
     *
     * @return array
     */
    abstract public function getRules(): array;

    public function testTestNameConversation(): void
    {
        foreach (array_diff($this->getNames(), $this->getNamesToSkip()) as $checkedName) {
            $this->validateValue($checkedName);
        }

        $this->assertTrue(empty($errors), implode(PHP_EOL, $this->errors));
    }

    private function validateValue(string $checkedName): void
    {
        foreach ($this->getRules() as $ruleName => $rule) {
            if (!$this->check($checkedName, $rule)) {
                $this->addError($checkedName, $ruleName);
            }
        }
    }

    private function check(string $checkedName, string $rule): bool
    {
        return preg_match($rule, $checkedName) ? true : false;
    }

    private function addError(string $checkedName, string $ruleName): void
    {
        $this->errors[] = "Value '$checkedName' does not match rule '$ruleName'";
    }
}
