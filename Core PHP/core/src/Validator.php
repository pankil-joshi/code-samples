<?php

namespace Quill;

class Validator extends \Valitron\Validator {

    function __construct($data, $fields = array(), $lang = null, $langDir = null) {

        parent::__construct($data, $fields, $lang, $langDir);

        parent::addRule('check_duplicate', function($field, $value, array $params, array $fields) {

            $database = new \Quill\Database();

            $database->setTableName($params[0]);

            $where = array($field . ' = ' => $value);
            if (!empty($params[1]) && !empty($params[2])) {

                $where[$params[1] . ' <> '] = $params[2];
            }

            if (!empty($params[3]) && !empty($params[4])) {

                $where[$params[3] . ' = '] = $params[4];
            }

            return empty($database->select($field)->where($where, true)->field(0));
        }, 'already exists.');

        parent::addRule('check_duplicate_user_email', function($field, $value, array $params, array $fields) {

            $database = new \Quill\Database();

            $database->setTableName($params[0]);

            $where = array($field . ' = ' => $value, 'is_active =' => 1);
            if (!empty($params[1]) && !empty($params[2])) {

                $where[$params[1] . ' <> '] = $params[2];
            }

            return empty($database->select($field)->where($where, true)->field(0));
        }, 'already exists.');
    }

    static function trimArray($input) {

        if (!is_array($input) && !is_object($input)) {

            return trim($input);
        } else {

            return $input;
        }
    }

    public function sanatized() {

        return array_map('self::trimArray', $this->_fields);
    }

}
