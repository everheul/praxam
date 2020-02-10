<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Exam;
use \App\Models\Scene;
use \App\Models\Question;
use \App\Models\Answer;

class ResetOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the order fields in scenes, questions and answers to the order of its id.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->info("Checking order fields...");
        $exams = Exam::select('id','name')
            ->with('scenes', 'scenes.questions', 'scenes.questions.answers')
            ->get();

        foreach($exams as $exam) {

            // scenes
            $sceneCount = 0;
            $oScenes = $exam->scenes->sortBy('id');
            foreach($oScenes as $scene) {
                $sceneCount++;
                $scene->order = $sceneCount;

                //- questions
                $questionCount = 0;
                $oQuestions = $scene->questions->sortBy('id');
                foreach($oQuestions as $question) {
                    $questionCount++;
                    $question->order = $questionCount;

                    // answers
                    $answerCount = 0;
                    $oAnswers = $question->answers->sortBy('id');
                    foreach($oAnswers as $answer) {
                        $answerCount++;
                        $answer->order = $answerCount;
                        $answer->save();
                    }

                    $question->answer_count = $answerCount;
                    $question->save();
                }

                $scene->question_count = $questionCount;
                $scene->save();
            }

            $this->info("Exam '{$exam->name}' has $sceneCount scenes.");
            $exam->scene_count = $sceneCount;
            $exam->save();
        }
        $this->info("Done.\n");
    }
}
