<?php

namespace App\Http\Controllers;

use App\Engagement;
use App\Http\Resources\Engagement as EngagementResource;
use App\Imports\EngagementsImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;
use Excel;
//use Illuminate\Support\Collection;
use App\Support\Collection;

class EngagementController extends Controller
{
    public function home()
    {
        return view('admin.engagements.index', [
            'page' => 'engagement',
            'sub_page' => 'engagement.list'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $field = 'id';
        $order = "desc";
        if($request->order !== NULL){
            $field = 'm_engage';
            $order = $request->order;
        }
        $engagements = Engagement::where('d_exerci', '=', $request->exercice)
            ->where(function ($query) use ($request){
                $query->where('n_engage', 'LIKE', '%' . $request->keyword . '%')
                    ->orWhere('l_dep', 'like', '%' . $request->keyword . '%')
                    ->orWhere('l_depeng', 'like', '%' . $request->keyword . '%')
                    ->orWhere('l_nmtir', 'like', '%' . $request->keyword . '%')
                    ->orWhere('n_mattir', 'like', '%' . $request->keyword . '%')
                    ->orWhere('c_mattir', 'like', '%' . $request->keyword . '%')
                    ->orWhere('l_chap', 'like', '%' . $request->keyword . '%')
                    ->orWhere('n_devis', 'like', '%' . $request->keyword . '%');
            })
            ->orderBy($field, $order)->get();

        //On récupere le montant total des engagements
        $amountEngage = 0;
        $amountPaye = 0;
        $amountNonPaye = 0;
        foreach ($engagements as $engagement) {
            $amountEngage += $engagement->m_engage;
            if($engagement->is_paid == true) {$amountPaye += $engagement->m_paye;}
        }

        $amountNonPaye = $amountEngage - $amountPaye;

        //On selectionne tous les engagements en retard
        if($request->late === 'true'){
            $engagements = $engagements->filter(function ($engagement) {
                return $engagement->is_late === true;
            });
        }

        //On filtre les engagements payés et non payés
        if($request->status !== NULL){
            if($request->status === 'paye'){
                $engagements = $engagements->filter(function ($engagement) {
                    return $engagement->is_paid == true;
                });
            }elseif($request->status === 'non_paye'){
                $engagements = $engagements->filter(function ($engagement) {
                    return $engagement->is_paid == false;
                });
            }
        }

        return [
              'm_engage' => $amountEngage,
              'm_paye' => $amountPaye,
              'm_non_paye' => $amountNonPaye,
              'resources' => EngagementResource::collection($engagements)->paginate(15),
          ];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function show(Engagement $engagement)
    {
//        $engagement = Engagement::findOrFail($engagement);

        return view('admin.engagements.show', [
            'engagement' => $engagement,
            'page' => 'engagement',
            'sub_page' => 'engagement.show'
        ]);
    }

    public function ajaxSelectSingle($engagement)
    {
        $engagement = Engagement::findOrFail($engagement);

        if(!empty($engagement))
            return new EngagementResource($engagement);
        else
            return new JsonResponse('error', 500);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function edit(Engagement $engagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Engagement $engagement)
    {
        //
    }

    public function import(Request $request){
        // $this->validate($request, [
        //     'file'  => 'required|mimes:xls,xlsx'
        // ]);

        $exercice = intval($request->exercice);

        Excel::import(new EngagementsImport(), request()->file('file'));
        //upload excel file after importing

        $fileName = $exercice.'-'.time().'.'.$request->file->extension();

        $request->file->move(public_path('uploads/excel-files'), $fileName);

        return new JsonResponse('success', 200);
    }

    public function engagementsParStatut(){
        $currentYear = date('Y');

        $engagements = Engagement::where('d_exerci', $currentYear)->get();
        $engagementPaye = 0;
        $engagementNonPaye = 0;

        foreach ($engagements as $engagement){
            if($engagement->is_paid === true)
                $engagementPaye++;
            else
                $engagementNonPaye++;
        }

        return new JsonResponse([
            'engagement_paye' => $engagementPaye,
            'engagement_non_paye' => $engagementNonPaye
        ]);

    }

    public function engagementsParAnnee(){
        $year = intval(date('Y'));
        $years = [];
        for($i = 10; $i > 0; $i--){
            array_push($years, $year--);
        }
        $years = array_reverse($years);

        $engagements = [];
        $engagementsPayes = [];
        $engagementsNonPayes = [];
        foreach ($years as $year){
            $allEngagements = Engagement::where('d_exerci', $year)->get();

            $nbre = count($allEngagements);
            array_push($engagements, $nbre);

            $nbrePayes = count($allEngagements->filter(function ($engagement) { return $engagement->is_paid == true; }));
            array_push($engagementsPayes, $nbrePayes);

            $nbreNonPayes = $nbre - $nbrePayes;
            array_push($engagementsNonPayes, $nbreNonPayes);
        }

        return [
            'years' => $years,
            'engagements' => $engagements,
            'payes' => $engagementsPayes,
            'non_payes' => $engagementsNonPayes
        ];
    }

    public function montantParAnnee(){
        $year = intval(date('Y'));
        $years = [];
        for($i = 10; $i > 0; $i--){
            array_push($years, $year--);
        }
        $years = array_reverse($years);

        $engagements = [];
        $engagementsPayes = [];
        $engagementsNonPayes = [];
        foreach ($years as $year){
            $allEngagements = Engagement::where('d_exerci', $year)->get();

            $total = $allEngagements->sum('m_engage');
            array_push($engagements, $total);

            $payes = ($allEngagements->filter(function ($engagement) { return $engagement->is_paid == true; }))->sum('m_paye');
            array_push($engagementsPayes, $payes);

            $nonPayes = $total - $payes;
            array_push($engagementsNonPayes, $nonPayes);
        }

        return [
            'years' => $years,
            'engagements' => $engagements,
            'payes' => $engagementsPayes,
            'non_payes' => $engagementsNonPayes
        ];
    }

    public function search()
    {
        return view('admin.engagements.search', [
            'page' => 'engagement.search',
            'sub_page' => 'engagement.search.list'
        ]);
    }

    public function engagementsByMutlicriteria(Request $request)
    {
        $devis = $request->devis;
        $start = $request->start;
        $end = $request->end;
        $provider = $request->provider;
        $status = $request->status;

        $engagements = Engagement::whereBetween('d_exerci', [$start, $end])
            ->where('l_nmtir', 'LIKE', '%'.$provider.'%')
            ->where('n_devis', 'LIKE', '%'.$devis.'%')
            ->orderBy('id', 'desc')
            ->get();

        //On filtre les engagements par statut
        if($status !== NULL){
            switch ($status) {
                case 'paye':
                    $engagements = $engagements->filter(function ($engagement) {
                        return $engagement->is_paid == true;
                    });
                    break;
                case 'paye_early':
                    $engagements = $engagements->filter(function ($engagement) {
                        return $engagement->is_paid_early == true;
                    });
                    break;
                case 'paye_later':
                    $engagements = $engagements->filter(function ($engagement) {
                        return $engagement->is_paid_later == true;
                    });
                    break;
                case 'non_paye':
                    $engagements = $engagements->filter(function ($engagement) {
                        return $engagement->is_paid == false;
                    });
                    break;
                case 'non_paye_later':
                    $engagements = $engagements->filter(function ($engagement) {
                        return ($engagement->is_paid == false && $engagement->is_late == true);
                    });
                    break;
                default:
                    // code...
                    break;
            }

        }

        // if($status){
        //     if($status == 'paye')
        //         $bool = true;
        //     else
        //         $bool = false;
        //
        //     $engagements = Engagement::whereBetween('d_exerci', [$start, $end])
        //         ->where('l_nmtir', 'LIKE', '%'.$provider.'%')
        //         ->where('n_devis', 'LIKE', '%'.$devis.'%')
        //         ->where('is_paid', $bool)
        //         ->orderBy('id', 'desc')
        //         ->paginate(15);
        // }

        return EngagementResource::collection($engagements)->paginate(15);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Engagement  $engagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Engagement $engagement)
    {
        //
    }
}
