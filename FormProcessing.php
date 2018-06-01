<?php

require "Config.php";

class FormProcessing extends Config
{
    public function run($post){

        $nickname = $post['nickname'];
        $name = $post['name'];
        $surname = $post['surname'];
        $email = $post['email'];
        $password = $post['password'];

        $testData = $this->testData($nickname,$name,$surname,$email,$password);

        //проверка данных
        if($testData != true){
            $this->message('Измените данные поля');
        }else{
            $result =$this->testNickname($nickname);

            //проверка на существование никнейма
            if(!empty($result)){
                $this->feedBack('Человек с таким никнеймом уже зарегистрирован');
            }else{
                //добавление данных
                $this->query($nickname,$name,$surname,$email,$password);
                $this->message('Ваши данные успешно добавлены');
            }
        }
    }

    //проверка на валидность данных
    private function testData($nickname,$name,$surname,$email,$password){
        $test_nickname = preg_match('/^[A-Za-z0-9]+$/',$nickname);
        $test_name = preg_match('/^[А-Яа-я0-9]+$/',$name);
        $test_surname = preg_match('/^[А-Яа-я0-9]+$/',$surname);
        $test_email = preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i',$email);

        if($test_nickname != false && $test_name != false && $test_surname != false && $test_email != false && mb_strlen($password) > 5){
            return true;
        }else{
            return false;
        }

    }

    private function feedBack($name){
        exit(json_encode(['name' => $name]));
    }

    public function message($name){
        exit(json_encode(['message' => $name]));
    }

    //функция добавления данных
    private function query($nickname,$name,$surname,$email,$password){
        $query = $this->db->prepare('INSERT INTO `users` (`id`,`nickname`,`name`,`surname`,`email`,`password`) 
                                                  VALUES (:id,:nickname,:name,:surname,:email,:password)');
        $query->bindValue(':id','');
        $query->bindValue(':nickname',$nickname);
        $query->bindValue(':name', $name);
        $query->bindValue(':surname',$surname);
        $query->bindValue(':email',$email);
        $query->bindValue(':password',$password);
        $query->execute();
    }

    //функция проверки никнейма
    private function testNickname($nickname){
        $query = $this->db->prepare('SELECT * FROM `users` WHERE `nickname` = :nickname');
        $query->bindValue(':nickname',$nickname);
        $query->execute();
        return $query->fetchAll();
    }

}

$test = new FormProcessing();
$test->run($_POST);