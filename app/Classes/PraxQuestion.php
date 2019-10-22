<?php

namespace App\Classes;

use App\Models\Question;
use App\Models\UserQuestion;
use App\Classes\PraxAnswer;

class PraxQuestion
{
    public $parent = NULL;          //-> PraxScene
    public $userquestion = NULL;    //-> UserQuestion
    public $question = NULL;        //-> Question
    public $praxanswers;            //-= PraxAnswers Collection
    public $locked = false;
    public $order = 0;              //-  used with type2 scenes

    /**
     * todo: not used yet
     * Load all the data we need to make a complete PraxScene piramide.
     *
     * @param int $userscene_id
     * @return PraxQuestion
     */
    public function loadUserQuestionData(int $userquestion_id) {
        //DB::enableQueryLog();
        $userquestion = UserQuestion::where('id', '=', $userquestion_id)
            ->with('useranswers','question','question.answers')
            ->firstOrFail();
        //dd(DB::getQueryLog());
        $this->setUserSceneData($userquestion);
        return $this;
    }

    /**
     * Create the rest of the PraxScene piramide.
     * todo: call setAdminQuestionData first?
     *
     * @param UserScene $userscene
     * @param UserExam|null $parent
     * @return PraxQuestion
     */
    public function setUserQuestionData(UserQuestion $userquestion, PraxScene $parent = NULL) {
        $this->parent = $parent;
        $this->userquestion = $userquestion;
        $this->question = $userquestion->question;
        $this->praxanswers = collect();

        //- create the PraxAnswers:
        //- userquestion->question has answers eagerloaded, because useranswers are optional.
        foreach($userquestion->question->answers as $answer) {
            $this->praxanswers->add((new PraxAnswer())->setAnswerData($answer, $this));
        }
        foreach($userquestion->useranswers as $useranswer) {
            //- any answer locks the question
            $this->locked = true;
            // todo: what if..?
            $this->praxanswers->firstWhere('answer.id', $useranswer->answer_id)->setUserAnswerData($useranswer);
        }
        return $this;
    }


    /**
     * Create a PraxScene pyramid without any user data - admin mode.
     *
     * @param Question $question
     * @param PraxScene|NULL $parent
     * @return $this
     */
    public function setAdminQuestionData(Question $question, PraxScene $parent = NULL) {
        $this->parent = $parent;
        $this->question = $question;
        $this->praxanswers = collect();

        //- create the PraxAnswers:
        foreach($question->answers as $answer) {
            $this->praxanswers->add((new PraxAnswer())->setAnswerData($answer, $this));
        }
        return $this;
    }


    /**
     * @param  int  $order
     * @return  PraxQuestion
     */
    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }

    /**
     * Returns the string that disables the 'Done' button if locked
     * @return string
     */
    public function disabledStr() {
        return ($this->locked) ? ' disabled=1' : '';
    }

    /**
     * used for scene type 2 questions
     *
     * @return mixed
     */
    public function checkedTabStr() {
        return ($this->locked) ? "&nbsp;&nbsp;<i class=\"fa fa-check-circle\" aria-hidden=\"true\"></i>" : ""; //   "<i class=\"fas fa-check\"></i>"" &#61452; "
    }
}
