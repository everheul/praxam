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

    /**
     * Load all the data we need to make a complete PraxScene piramide.
     *
     * @param int $userscene_id
     * @return $this
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
     *
     * @param UserScene $userscene
     * @param UserExam|null $parent
     * @return $this
     */
    public function setUserQuestionData(UserQuestion $userquestion, PraxScene $parent = NULL) {
        $this->parent = $parent;
        $this->userquestion = $userquestion;
        $this->question = $userquestion->question;
        $this->praxanswers = collect();
        //dd($userquestion);
        //- create the PraxAnswers:
        //- $userquestion->question also has its answers eagerloaded, because useranswers are optional.
        foreach($userquestion->question->answers as $answer) {
            $this->praxanswers->add((new PraxAnswer())->setAnswerData($answer, $this));
        }
        foreach($userquestion->useranswers as $useranswer) {
            $this->locked = true;
            // todo: what if..?
            $this->praxanswers->firstWhere('answer.id', $useranswer->answer_id)->setUserAnswerData($useranswer);
        }
        return $this;
    }

    /**
     * This FormId is only important when there is a userquestion, otherwise it just has to be unique.
     *
     * @return mixed
     */
    public function getFormId() {
        return (empty($this->userquestion)) ? $this->question->id : $this->userquestion->id;
    }

    /**
     * Returns the string that disables the 'Done' button if locked
     * @return string
     */
    public function disabledStr() {
        return ($this->locked) ? " disabled" : "";
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
