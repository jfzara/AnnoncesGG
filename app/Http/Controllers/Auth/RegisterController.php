<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new Utilisateurs as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect Utilisateurs after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
{
    return Validator::make($data, [
        'Nom' => ['required', 'string', 'max:25', 'regex:/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\' -]*$/'],
        'Courriel' => ['required', 'string', 'email', 'max:50', 'unique:utilisateurs'],
        'MotDePasse' => ['required', 'string', 'min:5', 'max:15', 'confirmed'],
        'NoTelMaison' => ['nullable', 'regex:/^\(\d{3}\) \d{3}-\d{4}[PN]$/'],
        'NoTelTravail' => ['nullable', 'regex:/^\(\d{3}\) \d{3}-\d{4} #\d{4}[PN]$/'],
        'NoTelCellulaire' => ['nullable', 'regex:/^\(\d{3}\) \d{3}-\d{4}[PN]$/'],
    ]);
}

    /**
     * Create a new Utilisateur instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Utilisateur
     */
    protected function create(array $data)
{
    return Utilisateur::create([
        'Nom' => $data['Nom'],
        'Courriel' => $data['Courriel'],
        'MotDePasse' => Hash::make($data['MotDePasse']),
        'Statut' => 0, // Statut initial (en attente)
        'NbConnexions' => 0, // Compteur de connexions initial
        'NoEmpl' => $data['NoEmpl'] ?? null,
        'Prenom' => $data['Prenom'],
        'NoTelMaison' => $data['NoTelMaison'],
        'NoTelTravail' => $data['NoTelTravail'],
        'NoTelCellulaire' => $data['NoTelCellulaire'],
        'AutresInfos' => $data['AutresInfos'] ?? null,
    ]);
}
}
