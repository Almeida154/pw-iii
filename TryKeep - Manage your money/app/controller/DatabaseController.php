<?php

namespace app\controller;
use app\core\Controller;
use app\classes\Input;
use app\classes\PHPMailer;
use app\model\User;
use app\model\MoneyMovement;
use app\model\Tag;
use app\model\Category;

class DatabaseController extends Controller {

    private $user;
    private $expense;
    private $income;
    private $expenseTag;
    private $incomeTag;

    private $category;

    function __construct() {
        $this->category = new Category(TB_CATEGORY);
        $this->user = new User(TB_USER);
        $this->income = new MoneyMovement(TB_MONEY_MOVEMENT, $this->category->getIncomeId());
        $this->expense = new MoneyMovement(TB_MONEY_MOVEMENT, $this->category->getExpenseId());
        $this->incomeTag = new Tag(TB_TAG, $this->category->getIncomeId());
        $this->expenseTag = new Tag(TB_TAG, $this->category->getExpenseId());
        session_start();
    }

    // Crud

    function insertUser() {
        $data = Input::post('userJSON');
        $json = json_decode(html_entity_decode($data));
        $json = json_encode($json);

        $user_id = $this->user->insert(json_decode($json));
        $this->firstIncome($user_id, json_decode($json));
        $this->firstExpense($user_id, json_decode($json));

        $this->sendEmail(
            json_decode($json)->email,
            "Be welcome, " . json_decode($json)->name,
            "We're glad that you have become part of the family. We hope you have a good experience."
        );

        $this->response($json);
    }

    function updateUser() {
        $this->user->update((Object) array(
            'id' => $_SESSION['id'],
            'name' => Input::post('name'),
            'email' => Input::post('email'),
            'password' => Input::post('password')
        ));
    }

    function deleteUser() {
        $this->user->delete($_SESSION['id']);
        session_destroy();
        sentinel();
    }

    function insertMM() {
        if (Input::post('tag') == null) {
            echo 'empty';
            return;
        }
        $obj = (Object) array(
            'user_id' => $_SESSION['id'],
            'title' => Input::post('title'),
            'tag_id' => (new Tag(TB_TAG, null))->findByTagName(Input::post('tag'))[0]->id,
            'category_id' => (new Tag(TB_TAG, null))->findByTagName(Input::post('tag'))[0]->category_id,
            'amount' => Input::post('hiddenAmount'),
            'description' => Input::post('description')
        );
        if ((new Tag(TB_TAG, null))->findByTagName(Input::post('tag'))[0]->category_id == $this->category->getIncomeId()) return $this->income->insert($obj);
        return $this->expense->insert($obj);
    }

    // Functions

    function sendEmail($email, $subject, $body) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;

        $mail->Username = 'trykeep2021@gmail.com';
        $mail->Password = 'bgk35670';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('trykeep2021@gmail.com', 'TRYKEEP');
        $mail->singleTo = true;

        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if(!$mail->Send()) {
            dd($mail->ErrorInfo);
        }
    }

    function sendPassword() {
        $email = Input::post('email');
        $user = $this->user->findByEmail($email);
        $name = $user->name;
        $password = $user->password;
        if ($this->user->countRowsByEmail($email) > 0) {
            $this->sendEmail($email, "Password Recovery", "Hello, " . $name . ". Your password is: " . $password);
            return;
        }
        echo -1;
    }

    function validateEmail() {
        $data = Input::post('email');
        if ($this->user->countRowsByEmail($data) > 0) { 
            echo 'false';
            return;
        }
        echo 'true';
    }

    private function initialize() {       
        $this->incomeTag->insert('Monthly Income');
        $this->expenseTag->insert('Monthly Expense');
    }

    private function response($json) {
        header("Content-type: application/json; charset=utf-8");
        echo $json;
    }

    private function firstIncome($user_id, $json) {
        try {
            $this->income->insert((Object) array(
                'title' => 'First income',
                'description' => 'Your first income :)',
                'amount' => $json->income,
                'user_id' => $user_id,
                'tag_id' => $this->incomeTag->findByTagName('Monthly Income')[0]->id
            ));
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    private function firstExpense($user_id, $json) {
        try {
            $this->expense->insert((Object) array(
                'title' => 'First expense',
                'description' => 'Your first expense :)',
                'amount' => $json->expense,
                'user_id' => $user_id,
                'tag_id' => $this->expenseTag->findByTagName('Monthly Expense')[0]->id
            ));
        } catch (\Throwable $th) {
            echo $th;
        }
    }

}