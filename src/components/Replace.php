<?php
namespace extas\components;

use extas\interfaces\IReplace;

/**
 * Class Replace
 *
 * Example of usage:
 *
 * $player = new Player();
 * $player->setName('jeyroik');
 *
 * $templates = [
 *     'My name is @name',
 *     'My player name is @player.name',
 *     'My sub-name is @sub.name'
 * ];
 * $values = [
 *      'name' => 'admin',
 *      'player' => $player,
 *      'sub' => [
 *          'name' => 'clear'
 *      ]
 * ];
 *
 * $result = Replace::please()
 *              ->apply($values)
 *              ->to($templates);
 * print_r($result);
 *
 * Result:
 * Array (
 *   'My name is admin',
 *   'My player name is jeyroik',
 *   'My sub-name is clear'
 * )
 *
 * Or with a single string:
 *
 * echo Replace::please()->apply(['name' => 'User'])->to('My name is @name');
 * // Result: My name is User
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class Replace implements IReplace
{
    /**
     * @var array
     */
    protected array $patterns = [];

    /**
     * @var array
     */
    protected array $values = [];

    /**
     * @return IReplace
     */
    public static function please(): IReplace
    {
        return new static();
    }

    /**
     * @param array $values
     *
     * @return IReplace
     */
    public function apply(array $values): IReplace
    {
        foreach ($values as $entity => $fields) {
            if (is_object($fields) && method_exists($fields, static::METHOD__TO_ARRAY)) {
                $fields = $fields->__toArray();
            }
            if (is_array($fields)) {
                foreach ($fields as $name => $value) {
                    if (is_array($value)) {
                        $this->apply([$this->escapeField($entity . '.' . $name) => $value]);
                    } elseif (is_object($value)) {
                        $value = method_exists($value, static::METHOD__TO_ARRAY)
                            ? $value->__toArray()
                            : (array)$value;
                        $this->apply([$this->escapeField($entity . '.' . $name) => $value]);
                    } else {
                        $this->patterns[] = $entity
                            ? $this->makeFieldPattern($entity, $name)
                            : $this->makePattern($name);
                        $this->values[] = $value;
                    }
                }
            } else {
                $this->patterns[] = $this->makePattern($entity);
                $this->values[] = $fields;
            }
        }

        return $this;
    }

    /**
     * @param string|string[] $templates
     *
     * @return string|string[]
     */
    public function to($templates)
    {
        $result = preg_replace($this->patterns, $this->values, $templates);

        /**
         * Reload
         */
        $this->patterns = [];
        $this->values = [];

        return $result;
    }

    /**
     * @param $entity
     * @param $field
     * @return string
     */
    protected function makeFieldPattern($entity, $field)
    {
        return '/\@' . $entity . '\.' . $field . '/i';
    }

    /**
     * @param $field
     * @return string
     */
    protected function makePattern($field)
    {
        preg_match('/\S+(_|\.|-)\S+/i', $field, $found);

        return empty($found) ? '/\@' . $field . '/i' : '/\@{' . $field . '}/i';
    }

    /**
     * @param $field
     * @return mixed
     */
    protected function escapeField($field)
    {
        return preg_replace(['/\@/i', '/^\\\./i'], ['\\@', '\\.'], $field);
    }
}
