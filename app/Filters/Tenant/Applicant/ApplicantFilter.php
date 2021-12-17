<?php


namespace App\Filters\Tenant\Applicant;

use App\Filters\FilterBuilder;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Repositories\Core\Status\StatusRepository;

class ApplicantFilter extends FilterBuilder
{
    public function appliedDate($qry)
    {
        $date = json_decode(htmlspecialchars_decode(request()->get('applied_date_range')), true);

        $qry->when($date, function ($qry) use ($date) {
            return $qry->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });

        return $this;
    }

    public function applicantJobPost($qry)
    {
        $user_id = auth()->user()->id;
        if (!auth()->user()->isAppAdmin()) {
            if (auth()->user()->can('can_view_applicant')) {
                $jobs = HiringTeam::where('recruiter_id', $user_id)->pluck('job_post_id')->toArray();
                $qry->when(request()->get('job'), function ($qry) use ($jobs) {
                    return $qry->where('job_post_id', request()->get('job'))
                        ->whereIn('job_post_id', $jobs);
                }, function ($qry) use ($jobs) {
                    return $qry->whereIn('job_post_id', $jobs);
                });
            }
            return $this;
        } else {
            $qry->when(request()->get('job'), function ($qry) {
                return $qry->where('job_post_id', request()->get('job'));
            });

            return $this;
        }
    }

    public function jobApplicantStatus($qry)
    {
        $qry->when(request()->get('status'), function ($qry) {
            return $qry->where('status_id', request('status'));
        },
            function ($qry) {
                return $qry->where('status_id', '!=', resolve(StatusRepository::class)
                    ->getStatusId('job_applicant', 'status_disqualified'))->latest('id');
            }
        );

        return $this;
    }

    public function jobApplicantReview($qry)
    {
        $qry->when(request()->get('review'), function ($qry) {

            return $qry->where('review', request()->get('review'));
        });

        return $this;
    }


}
