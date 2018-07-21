<?php

namespace Diploma\Http\Controllers;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 *  @author: Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class StudentMail extends Mailable implements ShouldQueue {
        use Queueable, SerializesModels;

        protected $student;
        protected $project;

        public function __construct($student, $project) {
            $this->student = $student;
            $this->project = $project;
        }

        public function build() {
            return $this->from('diplomacs18@gmail.com')
                    ->view('request_collaboration_mail')
                    ->subject('Intent for collaboration - Diploma CS')
                    ->with([
                        'student' => $this->student,
                        'project' => $this->project
                    ]);
        }
    }
