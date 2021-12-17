<?php

namespace App\Helpers\Tenant;

use App\Models\App\Applicant\Note;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Applicant\Event;
use App\Models\Tenant\JobPost\JobPost;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Core\Traits\FileHandler;
use App\Models\Tenant\Applicant\Attendee;
use App\Models\Tenant\Applicant\Applicant;
use App\Models\Tenant\Recruitment\JobStage;
use App\Models\Tenant\Applicant\JobApplicant;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Models\Tenant\Applicant\ApplicationAnswer;

class AppOnDeleteRelatedModels
{
    use FileHandler;

    private $data = [];
    private $model = '';
    private $should_delete_applicant = false;

    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    public function loadRelatedModelsOnDeleteJobApplicant()
    {
        $sql = <<<MULTI
            SELECT
                GROUP_CONCAT(ap.id SEPARATOR ', ') AS applicants,
                GROUP_CONCAT(e.id SEPARATOR ', ') AS 'events',
                GROUP_CONCAT(a.id SEPARATOR ', ') AS attendees,
                GROUP_CONCAT(n.id SEPARATOR ', ') AS notes,
                GROUP_CONCAT(aw.attachment SEPARATOR '|') AS application_answers_attachment,
                GROUP_CONCAT(aw.id SEPARATOR ',') AS application_answers

            FROM  job_applicants AS j 
                INNER JOIN applicants AS ap ON j.applicant_id = ap.id
                LEFT JOIN events AS e ON j.id = e.job_applicant_id            
                LEFT JOIN attendees AS a ON e.id = a.event_id
                LEFT JOIN notes AS n ON j.id = n.job_applicant_id
                LEFT JOIN application_answers AS aw ON j.id = aw.job_applicant_id
            
            WHERE j.id = {$this->model->id}
MULTI;

        $data = DB::select(DB::raw($sql))[0];

        $applicants = array_unique(explode(',', $data->applicants), SORT_NUMERIC);
        sort($applicants);

        $events = array_unique(explode(',', $data->events), SORT_NUMERIC);
        sort($events);

        $attendees = array_unique(explode(',', $data->attendees), SORT_NUMERIC);
        sort($attendees);

        $notes = array_unique(explode(',', $data->notes), SORT_NUMERIC);
        sort($notes);

        $application_answers_attachment = array_unique(explode('|', $data->application_answers_attachment), SORT_NUMERIC);
        sort($application_answers_attachment);

        $application_answers = array_unique(explode(',', $data->application_answers), SORT_NUMERIC);
        sort($application_answers);

        $this->data = [
            'applicants' => $applicants,
            'events' => $events,
            'attendees' => $attendees,
            'notes' => $notes,
            'application_answers_attachment' => $application_answers_attachment,
            'application_answers' => $application_answers

        ];
        return $this->data;
    }

    public function loadRelatedModelsOnDeleteApplicant()
    {
        $this->should_delete_applicant = true;
        $sql = <<<MULTI
            SELECT
                GROUP_CONCAT(j.id SEPARATOR ', ') AS job_applicants,
                GROUP_CONCAT(e.id SEPARATOR ', ') AS 'events',
                GROUP_CONCAT(a.id SEPARATOR ', ') AS attendees,
                GROUP_CONCAT(n.id SEPARATOR ', ') AS notes,
                GROUP_CONCAT(aw.attachment SEPARATOR '|') AS application_answers_attachment,
                GROUP_CONCAT(aw.id SEPARATOR ',') AS application_answers

            FROM  applicants AS ap 
                INNER JOIN job_applicants AS j ON j.applicant_id = ap.id
                LEFT JOIN events AS e ON j.id = e.job_applicant_id            
                LEFT JOIN attendees AS a ON e.id = a.event_id
                LEFT JOIN notes AS n ON j.id = n.job_applicant_id
                LEFT JOIN application_answers AS aw ON j.id = aw.job_applicant_id
            WHERE ap.id = {$this->model->id}
MULTI;

        $data = DB::select(DB::raw($sql))[0];

        $job_applicants = array_unique(explode(',', $data->job_applicants), SORT_NUMERIC);
        sort($job_applicants);

        $events = array_unique(explode(',', $data->events), SORT_NUMERIC);
        sort($events);

        $attendees = array_unique(explode(',', $data->attendees), SORT_NUMERIC);
        sort($attendees);

        $notes = array_unique(explode(',', $data->notes), SORT_NUMERIC);
        sort($notes);

        $application_answers_attachment = array_unique(explode('|', $data->application_answers_attachment), SORT_NUMERIC);
        sort($application_answers_attachment);

        $application_answers = array_unique(explode(',', $data->application_answers), SORT_NUMERIC);
        sort($application_answers);

        $this->data = [
            'job_applicants' => $job_applicants,
            'events' => $events,
            'attendees' => $attendees,
            'notes' => $notes,
            'application_answers_attachment' => $application_answers_attachment,
            'application_answers' => $application_answers,
        ];
        return $this->data;
    }

    public function loadRelatedModelsOnDeleteJObStage()
    {
        $sql = <<<MULTI
            SELECT
                GROUP_CONCAT(j.id SEPARATOR ', ') AS job_applicants,
                GROUP_CONCAT(e.id SEPARATOR ', ') AS 'events',
                GROUP_CONCAT(a.id SEPARATOR ', ') AS attendees,
                GROUP_CONCAT(ap.id SEPARATOR ', ') AS applicants,
                GROUP_CONCAT(n.id SEPARATOR ', ') AS notes,
                GROUP_CONCAT(aw.attachment SEPARATOR '|') AS application_answers_attachment,
                GROUP_CONCAT(aw.id SEPARATOR ',') AS application_answers
            FROM  job_stages as js
                LEFT JOIN job_applicants AS j ON js.id = j.current_stage_id    
                LEFT JOIN applicants AS ap ON j.applicant_id = ap.id
                LEFT JOIN events AS e ON j.id = e.job_applicant_id            
                LEFT JOIN attendees AS a ON e.id = a.event_id
                LEFT JOIN notes AS n ON j.id = n.job_applicant_id
                LEFT JOIN application_answers AS aw ON j.id = aw.job_applicant_id

            WHERE js.id = {$this->model->id}
MULTI;

        $data = DB::select(DB::raw($sql))[0];

        $job_applicants = array_unique(explode(',', $data->job_applicants), SORT_NUMERIC);
        sort($job_applicants);

        $events = array_unique(explode(',', $data->events), SORT_NUMERIC);
        sort($events);

        $attendees = array_unique(explode(',', $data->attendees), SORT_NUMERIC);
        sort($attendees);

        $applicants = array_unique(explode(',', $data->applicants), SORT_NUMERIC);
        sort($applicants);

        $notes = array_unique(explode(',', $data->notes), SORT_NUMERIC);
        sort($notes);

        $application_answers_attachment = array_unique(explode('|', $data->application_answers_attachment), SORT_NUMERIC);
        sort($application_answers_attachment);

        $application_answers = array_unique(explode(',', $data->application_answers), SORT_NUMERIC);
        sort($application_answers);

        $this->data = [
            'job_applicants' => $job_applicants,
            'events' => $events,
            'attendees' => $attendees,
            'applicants' => $applicants,
            'notes' => $notes,
            'application_answers_attachment' => $application_answers_attachment,
            'application_answers' => $application_answers,

        ];
        return $this->data;
    }

    public function loadRelatedModelsOnDeleteJobPost()
    {
        $sql = <<<MULTI
            SELECT 
                GROUP_CONCAT(h.id SEPARATOR ',') AS hiring_teams,
                GROUP_CONCAT(a.id SEPARATOR ',') AS attendees_by_hiring_team,
                GROUP_CONCAT(at.id SEPARATOR ',') AS attendees_by_event,
                GROUP_CONCAT(js.id SEPARATOR ',') AS job_stages,
                GROUP_CONCAT(ja.id SEPARATOR ',') AS job_applicants,
                GROUP_CONCAT(jap.id SEPARATOR ',') AS job_applicants_by_jobs,
                GROUP_CONCAT(e.id SEPARATOR ',') AS 'events',
                GROUP_CONCAT(n.id SEPARATOR ',') AS notes,
                GROUP_CONCAT(ap.id SEPARATOR ',') AS applicants,
                GROUP_CONCAT(aw.attachment SEPARATOR '|') AS application_answers_attachment,
                GROUP_CONCAT(aw.id SEPARATOR ',') AS application_answers

            FROM job_posts as j 

                LEFT JOIN hiring_teams AS h ON j.id = h.job_post_id
                LEFT JOIN job_applicants as jap ON j.id = jap.job_post_id
                LEFT JOIN attendees AS a ON h.id = a.hiring_team_id
                LEFT JOIN job_stages as js ON j.id = js.job_post_id
                LEFT JOIN job_applicants as ja ON js.id = ja.current_stage_id
                LEFT JOIN events as e ON e.job_applicant_id = ja.id
                LEFT JOIN attendees as at ON e.id = at.event_id 
                LEFT JOIN notes as n ON ja.id = n.job_applicant_id 
                LEFT JOIN applicants as ap ON ap.id = ja.applicant_id
                LEFT JOIN application_answers as aw ON ja.id = aw.job_applicant_id
            
            WHERE j.id = {$this->model->id}
MULTI;

        $data = DB::select(DB::raw($sql))[0];

        $hiring_teams = array_unique(explode(',', $data->hiring_teams), SORT_NUMERIC);
        sort($hiring_teams);

        $attendees = array_unique(array_merge(explode(',', $data->attendees_by_hiring_team), explode(',', $data->attendees_by_event)), SORT_NUMERIC);
        sort($attendees);

        $job_stages = array_unique(explode(',', $data->job_stages), SORT_NUMERIC);
        sort($job_stages);

        $job_applicants = array_unique(array_merge(explode(',', $data->job_applicants_by_jobs), explode(',', $data->job_applicants)), SORT_NUMERIC);
        sort($job_applicants);

        $events = array_unique(explode(',', $data->events), SORT_NUMERIC);
        sort($events);

        $notes = array_unique(explode(',', $data->notes), SORT_NUMERIC);
        sort($notes);

        $applicants = array_unique(explode(',', $data->applicants), SORT_NUMERIC);
        sort($applicants);

        $application_answers = array_unique(explode(',', $data->application_answers), SORT_NUMERIC);
        sort($application_answers);

        $application_answers_attachment = array_unique(explode('|', $data->application_answers_attachment), SORT_NUMERIC);
        sort($application_answers_attachment);

        $this->data = [
            'hiring_teams'=>$hiring_teams,
            'attendees'=>$attendees,
            'job_stages'=>$job_stages,
            'job_applicants'=>$job_applicants,
            'events'=>$events,
            'notes'=>$notes,
            'applicants'=>$applicants,
            'application_answers'=>$application_answers,
            'application_answers_attachment'=> $application_answers_attachment,
        ];

        return $this->data;
    }

    public function removeData()
    {
        $this->rmJobPost();
        $this->rmApplicationAnswers();

        $this->rmHiringTeam();
        $this->rmAttendees();

        $this->rmJobStage();
        $this->rmJobApplicant();

        $this->rmApplicant();
        $this->rmnotes();
        $this->rmEvents();
    }

    private function rmJobPost()
    {
        if (isset($this->data['job_posts']) && count($this->data['job_posts']) >= 1 && $this->data['job_posts'][0] > 0) {

            JobPost::destroy($this->data['job_posts']);
        }
    }

    private function rmApplicationAnswers()
    {
        if (isset($this->data['application_answers']) && count($this->data['application_answers']) >= 1 && $this->data['application_answers'][0] > 0) {
            ApplicationAnswer::destroy($this->data['application_answers']);
            $this->rmFiles($this->data['application_answers_attachment']);
        }
    }

    private function rmHiringTeam()
    {
        if (isset($this->data['hiring_teams']) && count($this->data['hiring_teams']) >= 1 && $this->data['hiring_teams'][0] > 0) {
            HiringTeam::destroy($this->data['hiring_teams']);
        }
    }

    private function rmAttendees()
    {
        if (isset($this->data['attendees']) && count($this->data['attendees']) >= 1 && $this->data['attendees'][0] > 0) {
            Attendee::destroy($this->data['attendees']);
        }
    }

    private function rmJobStage()
    {
        if (isset($this->data['job_stages']) && count($this->data['job_stages']) >= 1 && $this->data['job_stages'][0] > 0) {
            JobStage::destroy($this->data['job_stages']);
        }
    }

    private function rmJobApplicant()
    {

        if (isset($this->data['job_applicants']) && count($this->data['job_applicants']) >= 1 && $this->data['job_applicants'][0] > 0) {
            JobApplicant::destroy($this->data['job_applicants']);
        }
    }

    private function rmApplicant()
    {
        if (isset($this->data['applicants']) && count($this->data['applicants']) >= 1 && $this->data['applicants'][0] > 0) {
            $data = join(',', $this->data['applicants']);
            $sql = <<<EOD
                SELECT GROUP_CONCAT(a.id SEPARATOR ',') AS applicants
                FROM applicants as a 
                    LEFT JOIN `job_applicants` AS j ON a.id = j.applicant_id 
                WHERE j.applicant_id IS NULL AND a.id IN ({$data})
EOD;
            $data = DB::select(DB::raw($sql))[0];
            $data = explode(',', $data->applicants);
            Applicant::destroy($data);
        }
    }

    private function rmnotes()
    {
        if (isset($this->data['notes']) && count($this->data['notes']) >= 1 && $this->data['notes'][0] > 0) {
            Note::destroy($this->data['notes']);
        }
    }

    private function rmEvents()
    {
        if (isset($this->data['events']) && count($this->data['events']) >= 1 && $this->data['events'][0] > 0) {
            Event::destroy($this->data['events']);
        }
    }

    private function rmFiles($files = [])
    {
        if (count($files) === 0 || empty($files[0])) {
            return;
        }
        foreach ($files as $file) {
            if (!empty($file)) {
                $file = explode(',', $file);
                $this->deleteMultipleFile($file);
            }
        }
    }
}
