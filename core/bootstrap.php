<?php

// Instantiate content if not exist
// Loads after db.php

class Bootstrap {

    private $db; 
    
    /**
     * Construct class and check status
     *
     * @param  DB $db
     * @return void
     */
    public function __construct(DB $db) {
        $this->db = $db;
        if ($this->need_setup()){
            $this->setup();
        }
    }

    /**
     * Check if setup is needed
     *
     * @return bool
     */
    private function need_setup(){
        // check if tables 'questions' and 'responses' exist
        return !$this->db->check_table("questions") || !$this->db->check_table("responses");
    }

    /**
     * Setup tables
     *
     * @return void
     */
    private function setup(){
        // create tables
        $this->db->query("CREATE TABLE `questions` ( `id` INT NOT NULL AUTO_INCREMENT, `text` TEXT, `group` INT, `label` TEXT, `answers` TEXT, `randomize` INT, `target_responses` INT, `current_responses` TEXT, PRIMARY KEY (`id`) );");
        $this->db->query("CREATE TABLE `responses` ( `id` INT NOT NULL AUTO_INCREMENT, `questions` TEXT, `answers` TEXT, `time` DATETIME, `group` INT, PRIMARY KEY (`id`) );");
    }
}