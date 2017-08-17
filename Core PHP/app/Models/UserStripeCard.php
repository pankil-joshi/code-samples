<?php

namespace App\Models;

use Quill\Database as Database;

class UserStripeCard extends Database {

    public $tableName = 'user_stripe_cards';

    public function save($card) {

        if (isset($card['id']) && $card['id']) {

            $card['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->where(array('id' => $card['id']))->update($card, true);
        } else {

            $card['created_at'] = gmdate('Y-m-d H:i:s');
            $card['updated_at'] = gmdate('Y-m-d H:i:s');

            return $this->table()->insert($card, true);
        }
    }

    public function saveByCardId($card) {

        return $this->table()->where(array('stripe_card_id' => $card['stripe_card_id']))->update($card, true);
    }

    public function unsetDefault($card) {

        return $this->table()->where(array('user_id' => $card['user_id']))->update($card);
    }

    public function deleteByCardId($cardId) {

        return $this->table()->where(array('stripe_card_id' => $cardId))->delete();
    }

    public function getById($id) {

        return $this->table()->select()->where(array('id' => $id))->one();
    }

    public function getByCardId($cardId) {

        return $this->table()->select()->where(array('stripe_card_id' => $cardId))->one();
    }
    
    public function getAllByUserId($userId) {

        return $this->table()->select()
                        ->where(array('user_id' => $userId))
                        ->all();
    }

    public function getExpiredCards($userId) {

        return $this->table()->select()
                        ->where(array('user_id' => $userId))
                        ->where(array('exp_month < ' => gmdate('m'), 'exp_year <= ' => gmdate('Y')), true)
                        ->all();
    }

}
