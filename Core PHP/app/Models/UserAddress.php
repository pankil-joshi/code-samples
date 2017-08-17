<?php

namespace App\Models;

use Quill\Database as Database;

class UserAddress extends Database {

    public $tableName = 'user_addresses';

    public function save($address) {

        if (!empty($address['id'])) {

            $address['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $address['id'], 'user_id' => $address['user_id']))->update($address);
        } else {

            $address['created_at'] = gmdate('Y-m-d H:i:s');
            $address['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($address);
        }
    }

    public function unsetDefault($address) {

        return $this->table()->where(array('user_id' => $address['user_id']))->update($address);
    }

    public function remove($id, $userId) {

        return $this->table()->where(array('id' => $id, 'user_id' => $userId))->delete();
    }

    public function getById($id, $userId) {

        return $this->table()->select()->where(array('id' => $id, 'user_id' => $userId))->one();
    }

    public function getAllById($id, $userId) {

        return $this->table()->select()->where(array('id' => $id, 'user_id' => $userId))->all();
    }

    public function getAllByUserId($userId) {

        return $this->table()->select()->where(array('user_id' => $userId))->all();
    }

    public function getDefaultBillingByUserId($userId) {

        return $this->table()->select()->where(array('user_id' => $userId))->one();
    }

}
