<?php

namespace App\Http\Controllers;
use App\Http\Cart\Facades\Cart;
use App\Http\Models\Actors;
use App\Http\Models\Comments;
use App\Http\Models\Movies;
use App\Http\Models\Sessions;
use App\Http\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Netshell\Paypal\Facades\Paypal;


/**
 * Class MainController
 * V2 Fin de promotion
 * texte pour exemple
 * 2eme modif
 * @package App\Http\Controllers
 * Sufficé par le mot clef Controller
 * et doit hérité de la super classe Controller
 */
class MainController extends Controller{


    /**
     * Page Acceuil
     */
    public function index(){

        return view('Main/index');
    }


    /**
     * Ajax Movies
     * @param Request $request
     * @return mixed
     */
    public function ajaxmovies(Request $request){

        $validator = Validator::make(
            $request->all(),  //request all : tous les elements de requetses
            [
            'title' => 'required|min:10',
            ],[
            'title.required' => "Votre titre est obligatoire",
            'title.min' => "Votre titre est trop court"
            ]);

        if ($validator->fails()) { // si mon validateur échoue
            return $validator->errors()->all();
        }else{
            Movies::create([
                'title' => $request->title,
                'description' => $request->description,
                'categories_id' => $request->categories_id
            ]);

            return $request->title;
        }




    }


    /**
     * Done Payment
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function done(Request $request)
    {
        $id = $request->get('paymentId');
        $payer_id = $request->get('PayerID');

        $payment = PayPal::getById($id, $this->_apiContext);

        $paymentExecution = PayPal::PaymentExecution();

        $paymentExecution->setPayerId($payer_id);
        $executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        // Clear the shopping cart, write to database, send notifications, etc.
        $request->session()->pull('likes', []);

        return view('Main/index');
    }

    /**
     * Page Acceuil
     */
    public function dashboard(){


        $nbacteurs = Actors::count();
        $nbcommentaires = Comments::count();
        $nbmovies = Movies::count();
        $nbseances = Sessions::count();

        $actor = new Actors(); // Je récpere mon modèle
        $comment = new Comments(); // Je récpere mon modèle
        $movie = new Movies(); // Je récpere mon modèle
        $session = new Sessions(); // Je récpere mon modèle
        $user = new User(); // Je récpere mon modèle

        $avgacteurs = $actor->getAvgActors();
        $avgnotecommentaire = $comment->getAvgNote();
        $avgnotepresse = $movie->getAvgNotePresse();
        $avghour = $session->getAvgHourDate();

        $seances = $session->getNextSession();
        $users = $user->getLastUsers();

        return view('Main/dashboard',[
            'avgnotecommentaire' => $avgnotecommentaire->avgnote,
            'avgnotepresse' => $avgnotepresse->avgpress,
            'avgacteurs' => $avgacteurs->age,
            'avghour' => $avghour->avghour,
            'nbacteurs' => $nbacteurs,
            'nbcommentaires' => $nbcommentaires,
            'nbmovies' => $nbmovies,
            'nbseances' => $nbseances,
            'seances' => $seances,
            'users' => $users,
        ]);
    }


}


















