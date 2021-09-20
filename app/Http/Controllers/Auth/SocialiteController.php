<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\SocialAccount;
use App\Models\Customer;
use GuzzleHttp\Client;
use Laravel\Socialite\Two\InvalidStateException;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
    	return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
    	try{
            $socialite = Socialite::driver($provider)->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();
    		// $socialite = Socialite::driver($provider)->user();
    	}catch(InvalidStateException $e){
    		// $socialite = Socialite::driver($provider)->stateless()->user();            
        	return redirect()->route('customer-login');
    	}

        //Check user if already Registered or not
    	$authUser = $this->findOrCreateUser($socialite, $provider);

        //Login the user
        if (auth()->guard('user')->attempt(['email' => $authUser->email, 'password' => $provider])) {
            return redirect()->route('frontend.start');
        }
        return redirect()->route('customer-login')->withErrors(['email' => 'Email or password are wrong.']);
    }

    public function findOrCreateUser($socialUser, $provider)
    {
    	$socialAccount = SocialAccount::where('provider_id', $socialUser->getId())
    					->where('provider_name', $provider)
    					->first();
        
    	if($socialAccount){
            if($socialUser->getEmail()==Null || $socialUser->getEmail()=="Null" || $socialUser->getEmail()==""){                
                Customer::where('id', $socialAccount->customers_id)->update([
                    'name'     => $socialUser->getName(),
                    'email'    => $socialUser->getName(),
                    'password' => bcrypt($provider),
                ]);
            }else{
                Customer::where('id', $socialAccount->customers_id)->update([
                    'name'     => $socialUser->getName(),
                    'email'    => $socialUser->getEmail(),
                    'password' => bcrypt($provider),
                ]);
            }

    		$user = Customer::Where('id', $socialAccount->customers_id)->first(); 
    		return $user;
    	}else{
    		$user = Customer::Where('email', $socialUser->getEmail())->first();

    		if(!$user){

                
                if($socialUser->getEmail()==Null || $socialUser->getEmail()=="Null" || $socialUser->getEmail()==""){
                    $user = Customer::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getName(),
                        'password' => bcrypt($provider),
                        'minimum_price' => 0,
                        'maximum_price' => 0,
                        'minimum_price_land_area' => 0,
                        'maximum_price_land_area' => 0,
                        'minimum_land_area' => 0,
                        'maximum_land_area' => 0,
                    ]);
                }else{
                    $user = Customer::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => bcrypt($provider),
                        'minimum_price' => 0,
                        'maximum_price' => 0,
                        'minimum_price_land_area' => 0,
                        'maximum_price_land_area' => 0,
                        'minimum_land_area' => 0,
                        'maximum_land_area' => 0,
                    ]);
                }
    		}

    		$user->socialAccounts()->create([
				'provider_id' => $socialUser->getId(),
				'provider_name' => $provider
			]);

			return $user;
    	}
    }


}
