<?php
class Model_User {

    /**
     * @param int $id
     * @return Mapper_User
     */
    public function getUser($id) {
        return Mapper_UserTable::getInstance()->getUser($id)->fetchOne();
    }

    public function getUsersList() {
        $adapter = new Base_Paginator_Adapter_DoctrineQuery(Mapper_UserTable::getInstance()->getUsers());

        return new Zend_Paginator($adapter);
    }

    public function saveForm(Form_User_Edit $form)
    {
        $user = $this->getUser($form->getValue('id'));
        if(!$user) {
            $user = new Mapper_User();
        }

        $user->fromArray($form->getValues());

        if(empty($user->password)) { //password is empty only if this is new user creation
            $user->sendRegistered = true;
            $user->password = $this->generatePassword();
        }

        $user->save();

        return $user;

    }

    /**
     * @param Form_User_Login $form
     * @return stdClass
     */
    public function authenticate(Form_User_Login $form)
    {
        $email = $form->getValue('email');

        $userRow = Mapper_UserTable::getInstance()->findOneBy('email',$email);

        $return = new stdClass();
        $password = $form->getValue('password');
        if(!empty($password)) {
            if($userRow->active == 0) {
                $return->result = 'INACTIVE';
                $return->identity = $userRow;
            }
            elseif( $userRow->password === md5($password) ) {
                $return->result = 'OK';
                $return->identity = $userRow;
            } else {
                $return->result = 'ERROR';
                $return->identity = $userRow;
            }
        }

        return $return;

    }


    /**
     * @return string
     */
    public function generatePassword()
    {
        // set password length
        $length = 8;

        $password = '';
        $i = 0;

        // set ASCII range for random character generation
        $lower_ascii_bound = 50;  // "2"
        $upper_ascii_bound = 122; // "z"

                // Exclude special characters and some confusing alphanumerics
                // like o,O,0,I,1,l etc
                $notuse = array (58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);

                while ($i < $length) {
                        mt_srand ((double)microtime() * 1000000);

                        // random limits within ASCII table
                        $randnum = mt_rand ($lower_ascii_bound, $upper_ascii_bound);
                        if (!in_array ($randnum, $notuse)) {
                                $password = $password . chr($randnum);
                                $i++;
                        }
                }

        return $password;
    }
}