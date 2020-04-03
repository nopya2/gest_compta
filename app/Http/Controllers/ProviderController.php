<?php

namespace App\Http\Controllers;

use App\Echelon;
use App\Engagement;
use App\Provider;
use App\Http\Resources\Provider as ProviderResource;
use App\Http\Resources\Engagement as EngagementResource;
use App\Http\Resources\Echelon as EchelonResource;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function home()
    {
        return view('admin.providers.index', [
            'page' => 'provider',
            'sub_page' => 'provider.list'
        ]);
    }

    public function index(Request $request)
    {
        $providers = Provider::where('name', 'like', '%%')
            ->orderBy('name', 'ASC')
            ->get();

        return ProviderResource::collection($providers);
    }



    public function loadData(Request $request){

        $engagements = Engagement::where('l_nmtir', $request->provider)
            ->orderBy('id', 'DESC')
            ->get();

        $echelons = Echelon::whereHas('engagement', function ($query) use ($request){
                    $query->where('l_nmtir', $request->provider);
                })
            ->orderBy('id', 'desc')
            ->get();

        return [
            'engagements' => $engagements,
            'echelons' => $echelons
        ];

    }

    public function loadEngagements(Request $request){

        $engagements = Engagement::where('l_nmtir', $request->provider)
            ->where(function ($query) use ($request){
                $query
                    ->where('n_engage', 'LIKE', '%' . $request->keyword . '%')
                    ->orWhere('n_devis', 'LIKE', '%' . $request->keyword . '%');
            })
            ->orderBy('id', 'DESC')
            ->get();

        //On récupere le montant total des engagements
        $amountEngage = 0;
        $amountPaye = 0;
        $amountNonPaye = 0;
        foreach ($engagements as $engagement) {
            $amountEngage += $engagement->m_engage;
            if($engagement->is_paid == true) {$amountPaye += $engagement->m_paye;}
        }

        $amountNonPaye = $amountEngage - $amountPaye;

        return [
            'resources' => EngagementResource::collection($engagements)->paginate(10),
            'm_engage' => $amountEngage,
            'm_paye' => $amountPaye,
            'm_non_paye' => $amountNonPaye
        ];

    }

    public function loadEchelons(Request $request){

        $echelons = Echelon::whereHas('engagement', function ($query) use ($request){
                $query
                    ->where('l_nmtir', $request->provider)
                    ->where(function ($query) use ($request){
                        $query
                            ->where('n_engage', 'LIKE', '%' . $request->keyword . '%')
                            ->orWhere('n_devis', 'LIKE', '%' . $request->keyword . '%');
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return EchelonResource::collection($echelons);

    }
}
