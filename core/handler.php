<?php

// Faciliates data handling

class Handler {

    private $db;

    /**
     * Construct class and check status
     *
     * @param  mixed $db
     * @return void
     */
    public function __construct(DB $db) {
        $this->db = $db;
    }

    
    /**
     * Validate an answer submission
     *
     * @param  mixed $data  $_POST variable
     * @return array
     */
    public function validate_submission($data){
        // data should only be string numbers => 1,2,3,4,5 -- return array only with these values. 
        $data = array_filter($data, function($key, $value){
            return is_numeric($value) && is_numeric($key);
        }, ARRAY_FILTER_USE_BOTH);
        return $data;
    }
    
    /**
     * Store a submission
     * Update question counts as necessary
     *
     * @param  mixed $data   data as question_id => answer_id
     * @return void
     */
    public function store_submission($data){
        $group = $data['0'];
        unset($data['0']);
        $submission = array(
            "questions" => implode(",", array_keys($data)),
            "answers" => implode(",", array_values($data)),
            "time" => date("Y-m-d H:i:s"),
            "group" => $group
        );
        $this->db->insert("responses", $submission);

        // update question counts
        foreach ($data as $question_id => $answer_id) {
            $curr_q = $this->db->query("SELECT * FROM questions WHERE id = $question_id");
            $counter = json_decode($curr_q[0]['current_responses'], true);
            $counter[$group] += 1;
            $counter = json_encode($counter);
            $this->db->query("UPDATE questions SET current_responses = '$counter' WHERE id = $question_id");
        }
    }
        
    /**
     * Get questions from DB
     *
     * @param  int $rand   Randomized order questions or not
     * @return mixed
     */
    public function get_questions(int $rand){
        return $this->db->query("SELECT * FROM questions where randomize = $rand");
    }
    
    /**
     * Get and decode answers 
     * Translate data from question ids and answer ids to individual response items and content
     *
     * @return array
     */
    public function get_responses(){
        $data = $this->db->query("SELECT * FROM responses");
        $questions = $this->db->query("SELECT * FROM questions");
        $responses = [];
        foreach ($data as $response) {
            $response["questions"] = explode(",", $response["questions"]); // list of question ids
            $response['answers'] = explode(",", $response['answers']); // list of answer ids
            $response['items'] = []; // list of questions
            foreach ($response["questions"] as $key => $question) { 
                $data = [];
                $qm = array_search($question, array_column($questions, 'id')); 
                $data['question'] = $questions[$qm]['text'];
                $data['label'] = $questions[$qm]["label"];

                if (!empty($questions[$qm]["answers"])){
                    $answers = json_decode($questions[$qm]["answers"], true);
                } else {
                    $answers = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
                }

                $data['answer'] = $answers[$response["answers"][$key] - 1];
                $response['items'][] = $data;
            }
            $responses[] = $response;
        }
        return $responses;
    }
    
    /**
     * Filter out the questions so there's only one of each. Random assignment.
     * 
     * @param  mixed $grp    Group number to counter for
     * @return array
     */
    public function process_questions($grp){
        global $exists; //funky scoping
        $groups = [];
        $selected = [];
        $exists = [];

        $questions = $this->get_questions(1);
        shuffle($questions);

        foreach ($questions as $question){
            $qc = json_decode($question['current_responses'], true)[$grp];
            if ($qc < $question['target_responses'] || empty($qc)){
                $groups[$question["group"]][] = $question;
            }
        }

        function filter_used($array){
            global $exists;
            return !in_array($array['label'], $exists);
        }

        foreach ($groups as $group){
            $group = array_filter($group, "filter_used");
            $select = $group[array_rand($group)];
            $exists[] = $select["label"];
            $selected[] = $select;
        }

        shuffle($selected);
        $questions = $this->get_questions(0);
        $groups = [];
        foreach ($questions as $question){
            $groups[$question["group"]][] = $question;
        }
        foreach ($groups as $group){
            $selected[] = $group[array_rand($group)];
        }
        return $selected;
    }

}
