<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;



class DashboardController extends Controller
{
    public function getStats($role)
    {
        $data = [];

        if ($role === 'client') {
            // 1. Boutiques par localisation (Nombre de services enregistrés par ville)
            $data['villes'] = DB::table('localisation')
                ->join('service', 'localisation.id_localisation', '=', 'service.id_localisation')
                ->select('localisation.ville_localisation as label', DB::raw('count(*) as value'))
                ->groupBy('localisation.ville_localisation')
                ->get();

            // 2. Finances Client (Somme des totaux par jour sur les 7 derniers jours)
            $data['finances'] = DB::table('service')
                ->select(DB::raw('DATE(created_at) as label'), DB::raw('SUM(total_service) as value'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('label')
                ->orderBy('label')
                ->get();

            // 3. Répartition Informatique (Service) vs Ordinateurs (Service1)
            $data['repartition'] = [
                ['label' => 'Appareils Info', 'value' => DB::table('service')->count()],
                ['label' => 'Ordinateurs', 'value' => DB::table('service1')->count()]
            ];
        }

        if ($role === 'admin') {
            // 1. Total des users par rôle
            $data['users'] = DB::table('user')
                ->select('role as label', DB::raw('count(*) as value'))
                ->groupBy('role')->get();

            // 2. Total des boutiques (localisation)
            $data['localisations'] = DB::table('localisation')
                ->select('ville_localisation as label', DB::raw('count(*) as value'))
                ->groupBy('ville_localisation')->get();

            // 3. Total des électroménagers
            $data['electros'] = DB::table('electromenager')
                ->select('nom_electromenager as label', DB::raw('count(*) as value'))
                ->groupBy('nom_electromenager')->get();

            // 4. Total des ordinateurs
            $data['ordinateur'] = DB::table('ordinateur')
                ->select('nom_ordinateur as label', DB::raw('count(*) as value'))
                ->groupBy('nom_ordinateur')->get();

            // 5. Total des magasins (Stock par magasin)
            $data['magasins'] = DB::table('magasin')
                ->select('id_magasin as label', 'stock_magasin as value')->get();

            // 6. Total des services (Volume/Quantité vendue via table service)
            $data['services_vol'] = DB::table('service')
                ->select('description_service as label', DB::raw('count(*) as value'))
                ->groupBy('description_service')->get();

            // 7. Total des services1 (Volume/Quantité vendue via table service1)
            $data['services1_vol'] = DB::table('service1')
                ->select('description_service as label', DB::raw('count(*) as value'))
                ->groupBy('description_service')->get();
// 8. Prix unitaires des services
$data['services_prices'] = DB::table('service')
    ->select('description_service as label', 'prix_service as value')->get();

// 9. Prix unitaires des services1
$data['services1_prices'] = DB::table('service1')
    ->select('description_service as label', 'prix_service as value')->get();

// 10. Prix unitaires des formations (Vidéo)
$data['formations_prices'] = DB::table('formation')
    ->select('nom_formation as label', 'prix_formation as value')->get();

// 11. Prix unitaires des formations1 (Pdf)
$data['formations1_prices'] = DB::table('formation1')
    ->select('nom_formation as label', 'prix_formation as value')->get();
}
        return response()->json($data);
    }

}
