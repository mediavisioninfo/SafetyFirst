<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use App\Models\Custom;
use App\Models\Insurance;
use App\Models\InsurancePayment;
use App\Models\NoticeBoard;
use App\Models\PackageTransaction;
use App\Models\Policy;
use App\Models\Subscription;
use App\Models\Support;
use App\Models\User;
use App\Models\Claim;
use Carbon\Carbon;
use App\Models\ProfessionalFee;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (\Auth::check()) {
            $activeCompanyId = session('active_company_id');

            if (\Auth::user()->type == 'super admin') {
                $result['totalOrganization'] = User::where('type', 'owner')->count();
                $result['totalSubscription'] = Subscription::count();
                $result['totalTransaction'] = PackageTransaction::count();
                $result['totalIncome'] = PackageTransaction::sum('amount');
                $result['totalNote'] = NoticeBoard::where('parent_id', parentId())->count();
                $result['totalContact'] = Contact::where('parent_id', parentId())->count();
                $result['organizationByMonth'] = $this->organizationByMonth();
                $result['paymentByMonth'] = $this->paymentByMonth();

                return view('dashboard.super_admin', compact('result'));
            } else {
                $result['Claim Intimated'] = Claim::where('status', 'claim_intimated')
                    ->where('insurance_company_id', $activeCompanyId)->count();
                $result['Documents Pending'] = Claim::where('status', 'documents_pending')
                    ->where('insurance_company_id', $activeCompanyId)->count();
                $result['Documents Submitted'] = Claim::where('status', 'documents_submitted')
                    ->where('insurance_company_id', $activeCompanyId)->count();
                $result['Under Review'] = Claim::where('status', 'under_review')
                    ->where('insurance_company_id', $activeCompanyId)->count();
                $result['Rejected'] = Claim::where('status', 'rejected')
                    ->where('insurance_company_id', $activeCompanyId)->count();
                $result['Approved'] = Claim::where('status', 'approved')
                    ->where('insurance_company_id', $activeCompanyId)->count();

                $result['totalAgent'] = User::where('parent_id', parentId())
                    ->where('type','agent')
                    ->count();
                $result['totalPolicy'] = Policy::where('parent_id', parentId())->count();
                $result['totalInsurance'] = Insurance::where('parent_id', parentId())->count();
                $result['paymentOverview'] = $this->paymentOverview();
                $result['settings']=settings();

                return view('dashboard.index', compact('result', 'activeCompanyId'));
            }
        } else {
            if (!file_exists(setup())) {
                header('location:install');
                die;
            } else {
                $landingPage=getSettingsValByName('landing_page');
                if($landingPage=='on'){
                    $subscriptions=Subscription::get();
                    return view('layouts.landing',compact('subscriptions'));
                }else{
                    return redirect()->route('login');
                }
            }

        }

    }

    public function organizationByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));

        $currentdate = $start;

        $organization = [];
        while ($currentdate <= $end) {
            $organization['label'][] = date('M-Y', $currentdate);

            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $organization['data'][] = User::where('type', 'owner')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $currentdate = strtotime('+1 month', $currentdate);
        }


        return $organization;

    }

    public function paymentByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));

        $currentdate = $start;

        $payment = [];
        while ($currentdate <= $end) {
            $payment['label'][] = date('M-Y', $currentdate);

            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $payment['data'][] = PackageTransaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
            $currentdate = strtotime('+1 month', $currentdate);
        }

        return $payment;

    }

    // public function paymentOverview()
    // {
    //     $start = strtotime(date('Y-01'));
    //     $end = strtotime(date('Y-12'));

    //     $currentdate = $start;

    //     $payment = [];
    //     while ($currentdate <= $end) {
    //         $payment['label'][] = date('M-Y', $currentdate);

    //         $month = date('m', $currentdate);
    //         $year = date('Y', $currentdate);
    //         $payment['payment'][] = InsurancePayment::where('parent_id', parentId())->whereMonth('payment_date', $month)->whereYear('payment_date', $year)->sum('amount');
    //         $currentdate = strtotime('+1 month', $currentdate);
    //     }

    //     return $payment;

    // }

    public function paymentOverview()
    {
        // Get active company id from session
        $activeCompanyId = session('active_company_id');

        // Initialize month range for current year
        $start = Carbon::createFromDate(date('Y'), 1, 1);
        $end = Carbon::createFromDate(date('Y'), 12, 1);

        $payment = [
            'label' => [],
            'payment' => [],
        ];

        while ($start->lte($end)) {
            $month = $start->month;
            $year = $start->year;

            // Label like Jan-2025
            $payment['label'][] = $start->format('M-Y');

            // Query: sum for specific company and month
            $query = ProfessionalFee::whereMonth('created_at', $month)
                ->whereYear('created_at', $year);

            if ($activeCompanyId) {
                $query->where('company_id', $activeCompanyId);
            }

            $sum = $query->sum('total_amount');

            $payment['payment'][] = $sum;

            // Move to next month
            $start->addMonth();
        }

        return $payment;
    }

    public function switch($id)
    {
        // set new active company in session
        session(['active_company_id' => $id]);
        return redirect()->route('dashboard')->with('success', 'Switched company successfully.');
    }

}
